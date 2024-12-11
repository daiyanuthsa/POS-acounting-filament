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
use Filament\Support\RawJs;
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
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name', function ($query) {
                        $query->where('team_id', Auth::user()->teams()->first()->id);
                    })  // 'name' adalah kolom yang akan ditampilkan
                    ->required()
                    ->label('Nama Produk')
                    ->searchable()  // Tambahkan fitur pencarian
                    ->preload(),
                Forms\Components\Select::make('type')
                    ->required()
                    ->default('in')
                    ->options([
                        'in' => 'Stok Masuk',
                        'out' => 'Stok Keluar',
                    ])
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
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
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
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
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
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y - H:i:s', 'Asia/Jakarta')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
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
