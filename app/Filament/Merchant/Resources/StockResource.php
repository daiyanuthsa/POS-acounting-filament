<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StockResource\Pages;
use App\Filament\Merchant\Resources\StockResource\RelationManagers;
use App\Models\Stock;
use App\Models\StockMovement;
use Auth;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockResource extends Resource
{
    protected static ?string $model = StockMovement::class;


    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Shop';
    protected static ?string $navigationLabel = 'Stok Produk';
    protected static ?string $pluralModelLabel = 'Stok Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('team_id')
                    ->required()
                    ->numeric()
                    ->default(fn() => Auth::user()->teams()->first()->id)
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric()
                    ->default(fn() => Auth::id())
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name', function ($query) {
                        $query->where('team_id', Auth::user()->teams()->first()->id);
                    })  // 'name' adalah kolom yang akan ditampilkan
                    ->required()
                    ->label('Nama Produk')
                    ->searchable()  // Tambahkan fitur pencarian
                    ->preload(),
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->default('in')
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function ($set, $get) {
                        $quantity = floatval($get('quantity'));
                        $total = floatval($get('total'));
                        $unit_cost = floatval($get('unit_cost'));

                        if ($quantity > 0) {
                            if ($total > 0) {
                                // Jika quantity dan total diisi, hitung unit_cost
                                $set('unit_cost', $total / $quantity);
                            } elseif ($unit_cost > 0) {
                                // Jika quantity dan unit_cost diisi, hitung total
                                $set('total', $quantity * $unit_cost);
                            }
                        }
                    }),

                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->reactive()
                    ->afterStateUpdated(function ($set, $get) {
                        $quantity = floatval($get('quantity'));
                        $total = floatval($get('total'));
                        $unit_cost = floatval($get('unit_cost'));

                        if ($total > 0) {
                            if ($quantity > 0) {
                                // Jika total dan quantity diisi, hitung unit_cost
                                $set('unit_cost', $total / $quantity);
                            } elseif ($unit_cost > 0) {
                                // Jika total dan unit_cost diisi, hitung quantity
                                $set('quantity', $total / $unit_cost);
                            }
                        }
                    }),

                Forms\Components\TextInput::make('unit_cost')
                    ->numeric()
                    ->required()
                    ->prefix('Rp')
                    ->reactive()
                    ->afterStateUpdated(function ($set, $get) {
                        $quantity = floatval($get('quantity'));
                        $total = floatval($get('total'));
                        $unit_cost = floatval($get('unit_cost'));

                        if ($unit_cost > 0) {
                            if ($quantity > 0) {
                                // Jika unit_cost dan quantity diisi, hitung total
                                $set('total', $quantity * $unit_cost);
                            } elseif ($total > 0) {
                                // Jika unit_cost dan total diisi, hitung quantity
                                $set('quantity', $total / $unit_cost);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('notes')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y - H:i:s', 'Asia/Jakarta')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'in' => 'Masuk',
                            'out' => 'Keluar',
                            default => ucfirst($state),
                        };
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'out' => 'danger',
                        'in' => 'success',
                    }),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric(),
                Tables\Columns\TextColumn::make('unit_cost')
                    ->numeric()
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->money('IDR'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            // 'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}
