<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\OrderResource\Pages;
use App\Filament\Merchant\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Auth;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    protected static ?string $navigationGroup = 'Shop';
    protected static ?string $pluralModelLabel = 'Pesanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Repeater::make('products')
                    ->label('Pesanan')
                    ->relationship('products')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->options(function (callable $get, ?string $context = null) {
                                // Get current record ID if we're in edit mode
                                $currentId = $context;

                                $teamId = Auth::user()->teams()->first()->id;

                                // Get all selected products from repeater
                                $selectedProducts = collect($get('../../../productOrder') ?? [])
                                    ->filter(function ($item) use ($currentId) {
                                    return $item['product_id'] && $item['product_id'] !== $currentId;
                                })
                                    ->pluck('product_id')
                                    ->toArray();

                                // Query available products
                                return Product::query()
                                    ->where('team_id', $teamId)
                                    ->whereNotIn('id', $selectedProducts)
                                    ->get()
                                    ->mapWithKeys(function ($product) {
                                    return [
                                        $product->id => $product->name . ' (Stok: ' . $product->getLastBatchStock(Auth::user()->teams()->first()->id)->remaining_quantity . ')'
                                    ];
                                });
                            })
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->label('Nama Produk')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $qty = $get('qty');
                                if ($state) {
                                    $product = Product::find($state);
                                    if ($product) {
                                        // Set harga satuan produk
                                        $set('price', $product->price);

                                        // Set total jika qty ada
                                        if ($qty) {
                                            $set('total', floatval($qty) * floatval($product->price));
                                        }
                                    }
                                }
                            })
                            ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                // Set harga satuan saat form di-load ulang (edit mode)
                                if ($state) {
                                    $product = Product::find($state);
                                    if ($product) {
                                        $set('price', $product->price);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->disabled() // Harga satuan tidak bisa diubah manual oleh user
                            ->dehydrated(false) // Tidak akan disimpan ke database
                            ->reactive(),

                        Forms\Components\TextInput::make('qty')
                            ->required()
                            ->numeric()
                            ->reactive()->rules([
                                    'required',
                                    'numeric',
                                    'min:1',
                                ])
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $productId = $get('product_id');
                                if ($state && $productId) {
                                    $product = Product::find($productId);
                                    if ($product) {
                                        $availableStock = $product->getLastBatchStock(Auth::user()->teams()->first()->id)->remaining_quantity;

                                        // Jika quantity melebihi stok
                                        if ($state > $availableStock) {
                                            // Reset quantity ke stok maksimum
                                            $set('qty', $availableStock);
                                            $state = $availableStock;

                                            // Tampilkan notifikasi
                                            Notification::make()
                                                ->warning()
                                                ->title('Quantity melebihi stok')
                                                ->body("Quantity disesuaikan dengan stok tersedia ({$availableStock})")
                                                ->send();
                                        }

                                        // Update total
                                        $set('total', floatval($state) * floatval($product->price));
                                    }
                                }
                            })
                            ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                $productId = $get('product_id');
                                if ($state && $productId) {
                                    $product = Product::find($productId);
                                    if ($product) {
                                        $set('total', floatval($state) * floatval($product->price));
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('total')
                            ->label('Sub Total')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->reactive()
                    ])
                    ->minItems(1)
                    ->columns(4)
                    ->columnSpan(4)
                    ->afterStateUpdated(
                        function ($state, callable $set, callable $get) {
                            $productOrders = $get('products');

                            if ($productOrders) {
                                $grandTotal = collect($productOrders)->sum(function ($productOrder) {
                                    if (isset($productOrder['qty']) && isset($productOrder['product_id'])) {
                                        $product = Product::find($productOrder['product_id']);
                                        return $product ? floatval($productOrder['qty']) * floatval($product->price) : 0;
                                    }
                                    return 0;
                                });

                                $set('payment_amount', $grandTotal);
                            }
                        }
                    ),
                Forms\Components\Select::make('payment_type')
                    ->options([
                        'cash' => 'Cash',
                        'qris' => 'Qris',
                        'transfer' => 'Tranfer',
                    ])
                    ->label('Jenis Pembayaran')
                    ->required(),
                Forms\Components\TextInput::make('payment_amount')
                    ->prefix('IDR')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime('d M Y - H:i:s', 'Asia/Jakarta')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_type')
                    ->alignCenter()
                    ->label('Tipe Pembayaran'),
                Tables\Columns\TextColumn::make('payment_amount')
                    ->label('Jumlah Pembayaran')
                    ->numeric()
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),

        ];
    }
}
