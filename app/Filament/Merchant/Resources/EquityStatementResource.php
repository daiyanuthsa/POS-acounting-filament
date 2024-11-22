<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\EquityStatementResource\Pages;
use App\Filament\Merchant\Resources\EquityStatementResource\RelationManagers;
use App\Models\Account;
use App\Models\CashFlow;
use App\Models\LabaRugi;
use Auth;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class EquityStatementResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
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
                    ->getStateUsing(function (Account $record) {
                        $opening_balance = $record->opening_balance;
                        $labaRugi = self::calculateRevenueBeforeYear();
                        if ($record->code === '3-310') {
                            $opening_balance += $labaRugi;
                        }
                        return $opening_balance;
                    })
                    ->summarize(Sum::make()->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 2))),
                Tables\Columns\TextColumn::make('movement')
                    ->label('Perubahan')
                    ->money('IDR')
                    ->getStateUsing(function (Account $record) {
                        $movement = $record->movement;
                        $labaRugi = self::calculateLabaRugi();
                        if ($record->code === '3-310') {
                            $movement += $labaRugi;
                        }
                        return $movement;
                    })
                    ->summarize(Sum::make()->formatStateUsing(fn($state) => 'Rp ' . number_format($state + self::calculateLabaRugi(), 2))),
                Tables\Columns\TextColumn::make('closing_balance')
                    ->label('Saldo Akhir')
                    ->money('IDR')
                    ->getStateUsing(function (Account $record) {
                        $closingBalance = $record->closing_balance;
                        $saldoawal = self::calculateRevenueBeforeYear();
                        $labaRugi = self::calculateLabaRugi();
                        if ($record->code === '3-310') {
                            $closingBalance += $labaRugi;
                            $closingBalance += $saldoawal;
                        }
                        return $closingBalance;
                    })

                    ->summarize([Sum::make()->formatStateUsing(fn($state) => 'Rp ' . number_format($state + (self::calculateLabaRugi() + self::calculateRevenueBeforeYear()) , 2))])
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
                    ->selectablePlaceholder(false)
                    ->query(function (Builder $query) {
                        // Tidak memengaruhi query utama tabel
                        return $query;
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->header(function () {
                return view('partials.reload-notification');
            })
            ->description('Manage your clients here.')
            ->heading('Clients')
            ->striped()
            ->hiddenFilterIndicators()
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
                SELECT COALESCE(SUM(
                    CASE 
                        WHEN cf1.type = 'credit' THEN cf1.amount 
                        WHEN cf1.type = 'debit' THEN -cf1.amount 
                        ELSE 0 
                    END
                ), 0)
                FROM accounts AS a1
                LEFT JOIN cash_flows AS cf1 ON cf1.account_id = a1.id 
                    AND cf1.transaction_date < '{$startDate}'
                WHERE a1.id = accounts.id
            ) / 100 as opening_balance"),
                DB::raw("(
                SELECT COALESCE(SUM(
                    CASE 
                        WHEN cf2.type = 'credit' THEN cf2.amount 
                        WHEN cf2.type = 'debit' THEN -cf2.amount 
                        ELSE 0 
                    END
                ), 0)
                FROM accounts AS a2
                LEFT JOIN cash_flows AS cf2 ON cf2.account_id = a2.id 
                    AND cf2.transaction_date BETWEEN '{$startDate}' AND '{$endDate}'
                WHERE a2.id = accounts.id
            ) / 100 as movement"),
                DB::raw("(
                SELECT COALESCE(SUM(
                    CASE 
                        WHEN cf3.type = 'credit' THEN cf3.amount 
                        WHEN cf3.type = 'debit' THEN -cf3.amount 
                        ELSE 0 
                    END
                ), 0)
                FROM accounts AS a3
                LEFT JOIN cash_flows AS cf3 ON cf3.account_id = a3.id 
                    AND cf3.transaction_date <= '{$endDate}'
                WHERE a3.id = accounts.id
            ) / 100 as closing_balance")
            ])
            ->where('accounts.accountType', 'Equity')
            ->where('accounts.team_id', $teamId)
            ->orderBy('accounts.code')
            ->orderBy('accounts.accountName');
    }
    // public static function getEloquentQuery(): Builder
    // {
    //     $year = request()->input('tableFilters.year.value', date('Y'));
    //     $startDate = "{$year}-01-01";
    //     $endDate = "{$year}-12-31";
    //     $teamId = auth()->user()->teams()->first()?->id; // Menggunakan safe navigation operator untuk menghindari error jika tim tidak ada.

    //     return Account::query()
    //         ->select([
    //             'accounts.id',
    //             'accounts.code',
    //             'accounts.accountName',
    //             'accounts.accountType',
    //             DB::raw("0 AS opening_balance"),
    //             DB::raw("0 AS movement"),
    //             DB::raw("0 AS closing_balance"),
    //         ])
    //         ->where('accounts.accountType', 'Equity')
    //         ->where('accounts.team_id', $teamId)
    //         ->orderBy('accounts.code')
    //         ->orderBy('accounts.accountName');
    // }



    protected static function calculateLabaRugi(): float
    {
        $year = request()->input('tableFilters.year.value', date('Y'));
        $team_id = Auth::user()->teams()->first()->id;
        $totals = LabaRugi::query()
            ->where('team_id', $team_id)
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

    protected static function calculateRevenueBeforeYear(): float
    {

        $year = request()->input('tableFilters.year.value', date('Y'));
        $totals = LabaRugi::query()
            ->select('type')
            ->selectRaw('SUM(debit - credit) as total')
            ->whereYear('transaction_date', '<', $year)
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
