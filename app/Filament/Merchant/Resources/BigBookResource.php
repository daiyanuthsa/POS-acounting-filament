<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\BigBookResource\Pages;
use App\Models\BigBook;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Grouping\Group;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BigBookResource extends Resource
{
    protected static ?string $model = BigBook::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    protected static ?string $navigationGroup = 'Accountings';
    protected static ?string $navigationLabel = 'Buku Besar';
    protected static ?string $pluralModelLabel = 'Buku Besar';
    protected static ?int $navigationSort = 3;


    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('account.accountName')
            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Transaction Date')
                    ->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('account.code') // Assuming the relationship to account exists
                    ->label('Kode')
                ,
                Tables\Columns\TextColumn::make('account.accountName') // Assuming the relationship to account exists
                    ->label('Account')
                    ->sortable(),
                Tables\Columns\TextColumn::make('debit')
                    ->label('Debit Amount')
                    ->summarize(
                        Sum::make()
                            ->label('Debit Total')
                            ->formatStateUsing(fn($state) => 'IDR ' . number_format($state / 100, 2))
                    )
                    ->money('IDR'), // Formatting as money in IDR
                Tables\Columns\TextColumn::make('credit')
                    ->label('Credit Amount')
                    ->summarize(Sum::make()->label('Credit Total')->formatStateUsing(function ($state) {
                        // Format total sum dengan format uang
                        return 'IDR ' . number_format($state / 100, 2);
                    }))
                    // ->summarize(Sum::make()->label('Credit Total'))
                    ->money('IDR'),
            ])
            ->filters([
                Tables\Filters\Filter::make('transaction_month')
                    ->form([
                        Forms\Components\Select::make('month')
                            ->label('Bulan')
                            ->options([
                                '01' => 'January',
                                '02' => 'February',
                                '03' => 'March',
                                '04' => 'April',
                                '05' => 'May',
                                '06' => 'June',
                                '07' => 'July',
                                '08' => 'August',
                                '09' => 'September',
                                '10' => 'October',
                                '11' => 'November',
                                '12' => 'December',
                            ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['month'],
                                fn(Builder $query, $month): Builder => $query->whereMonth('transaction_date', $month)
                            );
                    }),
                Tables\Filters\Filter::make('transaction_year')
                    ->form([
                        Forms\Components\Select::make('year')
                            ->label('Tahun')
                            ->default(Carbon::now()->year)
                            ->options(options: function () {
                                return BigBook::selectRaw('YEAR(transaction_date) as year')
                                    ->distinct()
                                    ->orderBy('year', 'desc')
                                    ->pluck('year', 'year')
                                    ->toArray();
                            })
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['year'],
                                fn(Builder $query, $year): Builder => $query->whereYear('transaction_date', $year)
                            );
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->groups([
                Group::make('account.accountName')
                    ->collapsible()
            ])
            ->groupingSettingsHidden()
            ->actions([
            ])
            ->bulkActions([

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
            'index' => Pages\ListBigBooks::route('/'),
            // 'create' => Pages\CreateBigBook::route('/create'),
            // 'view' => Pages\ViewBigBook::route('/{record}'),
            // 'edit' => Pages\EditBigBook::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Menonaktifkan tombol "New Big Book"
    }

    public static function getWidgets(): array
    {
        return [

        ];
    }
}
