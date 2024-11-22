<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\BalanceSheetResource\Pages;

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

class BalanceSheetResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $pluralModelLabel = 'Laporan Posisi Keuangan';
    protected static ?string $navigationGroup = 'Accountings';
    protected static ?int $navigationSort = 6;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Nama Akun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('accountName')
                    ->label('Nama Akun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('calculated_balance')
                    ->label('Saldo')
                    ->money('IDR')

                    ->getStateUsing(function (Account $record) {
                        $calculated_balance = $record->calculated_balance;
                        if ($record->accountName === 'Prive') {
                            $calculated_balance += self::calculateRevenue();

                        }
                        return 'Rp ' . number_format($calculated_balance / 100, 2);
                    })
                    ->summarize(
                        Sum::make()
                            ->label('Saldo Akun')
                            ->formatStateUsing(function ($state) {
                                $revenue = 0;
                                $tab = request('activeTab', 'Aktiva');
                                if ($tab === 'Pasiva') {
                                    $revenue += self::calculateRevenue();
                                }
                                // Format total sum dengan format uang
                                return 'Rp ' . number_format(($state + $revenue) / 100, 2);
                            })
                    ),
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
                        // Filter query untuk mengembalikan semua transaksi pada tahun yang dipilih dan sebelumnya
                        if (isset($data['value'])) {
                            $query->whereYear('cash_flows.transaction_date', '<=', $data['value']);
                        }
                        return $query;
                    })->selectablePlaceholder(false),
            ], layout: FiltersLayout::AboveContent)

            ->header(function () {
                return view('partials.reload-notification');
            })

            ->striped()
            ->poll('10s');
    }

    public static function getEloquentQuery(): Builder
    {
        $year = request('tableFilters.year', date('Y')); // Ambil tahun dari filter atau gunakan tahun saat ini
        $teamId = auth()->user()->teams()->first()->id; // Ambil ID tim dari pengguna yang sedang login

        $query = parent::getEloquentQuery()
            ->leftJoin('cash_flows', function ($join) use ($teamId) {
                $join->on('accounts.id', '=', 'cash_flows.account_id')
                    ->where('cash_flows.team_id', '=', $teamId); // Tetap hubungkan berdasarkan tim
            })
            ->select('accounts.*')
            ->addSelect([
                'calculated_balance' => DB::raw("
                COALESCE(
                    SUM(
                        CASE
                            WHEN YEAR(cash_flows.transaction_date) <= $year THEN 
                                CASE
                                    WHEN accounts.accountType IN ('Liability', 'Equity', 'Revenue') AND cash_flows.type = 'credit' THEN cash_flows.amount
                                    WHEN accounts.accountType IN ('Asset', 'Expense') AND cash_flows.type = 'debit' THEN cash_flows.amount
                                    ELSE 0
                                END
                            ELSE 0
                        END
                    ),
                    0
                ) AS calculated_balance
            "),
            ])
            ->groupBy('accounts.id')
            ->orderBy('accounts.code');
        
        return $query;
    }



    protected static function calculateRevenue(): float
    {
        $year = request('tableFilters.year', date('Y')); // Ambil tahun dari filter atau gunakan tahun saat ini
        $teamId = auth()->user()->teams()->first()->id; // Ambil ID tim dari pengguna yang sedang login
        $totals = LabaRugi::query()
            ->where('team_id', $teamId)
            ->select('type')
            ->selectRaw('SUM(debit - credit) as total')
            ->whereYear('transaction_date', '<=', $year)
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        $pendapatan = abs($totals['Revenue'] ?? 0);
        $pengeluaran = abs($totals['Expense'] ?? 0);
        $hpp = abs($totals['UPC'] ?? 0);

        return $pendapatan - $pengeluaran - $hpp;
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
            'index' => Pages\ListBalanceSheets::route('/'),
        ];
    }
}
