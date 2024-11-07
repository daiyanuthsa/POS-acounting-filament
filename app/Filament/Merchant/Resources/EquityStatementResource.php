<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\EquityStatementResource\Pages;
use App\Filament\Merchant\Resources\EquityStatementResource\RelationManagers;
use App\Models\Account;
use App\Models\CashFlow;
use App\Models\LabaRugi;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class EquityStatementResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static ?string $navigationLabel = 'Equity Statement';
    protected static ?string $navigationGroup = 'Accountings';
    protected static ?string $pluralModelLabel = 'Laporan Perubahan Modal';
    protected static ?int $navigationSort = 5;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Akun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('accountName')
                    ->label('Nama Akun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('opening_balance')
                    ->label('Saldo Awal')
                    ->money('IDR')
                    ->getStateUsing(fn(Account $record) => $record->opening_balance)
                    ->summarize(Sum::make()->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 2))),
                Tables\Columns\TextColumn::make('movement')
                    ->label('Perubahan')
                    ->money('IDR')
                    ->getStateUsing(function (Account $record) {
                        $movement = $record->movement;
                        if ($record->accountName === 'Modal Pemilik') {
                            $labaRugi = self::calculateLabaRugi();
                            $movement += $labaRugi;
                        }
                        return $movement;
                    })
                    ->summarize(Sum::make()->formatStateUsing(fn($state) => 'Rp ' . number_format($state + self::calculateLabaRugi(), 2))),
                Tables\Columns\TextColumn::make('closing_balance')
                    ->label('Saldo Akhir')
                    ->money('IDR')
                    ->getStateUsing(function (Account $record) {
                        $closingBalance = $record->closing_balance / 100;
                        if ($record->accountName === 'Modal Pemilik') {
                            $labaRugi = self::calculateLabaRugi();
                            $closingBalance += $labaRugi;
                        }
                        return $closingBalance;
                    })

                    ->summarize([Sum::make()->formatStateUsing(fn($state) => 'Rp ' . number_format($state / 100 + self::calculateLabaRugi(), 2))])
                ,

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->options(function () {
                        return CashFlow::selectRaw('YEAR(transaction_date) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year')
                            ->toArray();
                    })
                    ->label('Tahun')
                    ->default(date('Y'))
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['value'], function (Builder $query, $year) {
                            $query->whereHas('cashFlow', function (Builder $query) use ($year) {
                                $query->whereYear('transaction_date', '<=', $year);
                            });
                        });
                    }),
            ], layout: FiltersLayout::AboveContent)
            // ->defaultGroup('accountName')
            ->striped()
            ->poll('10s');
    }

    public static function getEloquentQuery(): Builder
    {
        $year = request()->input('tableFilters.year.value', date('Y'));
        $startDate = "{$year}-01-01";
        $endDate = "{$year}-12-31";
        $teamId = auth()->user()->teams()->first()->id;

        return Account::query()
            ->select([
                'accounts.id',
                'accounts.code',
                'accounts.accountName',
                'accounts.accountType',
                DB::raw("(
                    SELECT COALESCE(SUM(CASE WHEN type = 'credit' THEN amount ELSE -amount END), 0)
                    FROM cash_flows
                    WHERE account_id = accounts.id AND transaction_date < '{$startDate}'
                ) / 100 as opening_balance"),
                DB::raw("(
                    SELECT COALESCE(SUM(CASE WHEN type = 'credit' THEN amount ELSE -amount END), 0)
                    FROM cash_flows
                    WHERE account_id = accounts.id AND transaction_date BETWEEN '{$startDate}' AND '{$endDate}'
                ) / 100 as movement"),
                DB::raw("(
                    SELECT COALESCE(SUM(CASE WHEN type = 'credit' THEN amount ELSE -amount END), 0)
                    FROM cash_flows
                    WHERE account_id = accounts.id AND transaction_date <= '{$endDate}'
                ) as closing_balance")
            ])
            ->where('accountType', 'Equity')
            ->where('team_id', $teamId)
            ->whereExists(function ($query) use ($year) {
                $query->select(DB::raw(1))
                    ->from('cash_flows')
                    ->whereColumn('cash_flows.account_id', 'accounts.id')
                    ->whereYear('transaction_date', '<=', $year);
            })
            ->orderBy('code')
            ->orderBy('accountName');
    }
    protected static function calculateLabaRugi(): float
    {
        $year = request()->input('tableFilters.year.value', date('Y'));
        $totals = LabaRugi::query()
            ->select('type')
            ->selectRaw('SUM(debit - credit) as total')
            ->whereYear('transaction_date', $year)
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        $pendapatan = abs($totals['Revenue'] ?? 0);
        $pengeluaran = abs($totals['Expense'] ?? 0);
        $hpp = abs($totals['UPC'] ?? 0);

        return ($pendapatan - $pengeluaran - $hpp) / 100;
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
            'index' => Pages\ListEquityStatements::route('/'),
        ];
    }
}
