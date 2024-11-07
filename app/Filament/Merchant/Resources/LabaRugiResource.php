<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\LabaRugiResource\Pages;
use App\Filament\Merchant\Resources\LabaRugiResource\RelationManagers;
use App\Models\BigBook;
use App\Models\LabaRugi;
use Filament\Forms;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class LabaRugiResource extends Resource
{
    protected static ?string $model = LabaRugi::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Accountings';
    protected static ?string $navigationLabel = 'Laba Rugi';
    protected static ?string $pluralModelLabel = 'Laba Rugi';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('account') // Assuming the relationship to account exists
                    ->label('Account'),
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
                    ->money('IDR'),
            ])
            ->filters([
                Filter::make('from_date')
                    ->form([
                        DatePicker::make('from_date')
                            ->label('Dari Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['from_date'],
                            fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date)
                        );
                    }),
                Filter::make('to_date')
                    ->form([
                        DatePicker::make('to_date')
                            ->label('Sampai Tanggal')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['to_date'],
                            fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date)
                        );
                    })
                    ->default(true),
            ], layout: FiltersLayout::AboveContent)->filtersFormColumns(4)
            ->groupsOnly()
            ->defaultGroup('account')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListLabaRugis::route('/'),
        ];
    }
}
