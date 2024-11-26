<?php

namespace App\Filament\Merchant\Resources;

use App\Casts\MoneyCast;
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
                Tables\Columns\TextColumn::make('accountType')
                    ->label('Nama Akun')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'Asset' => 'Aktiva',
                            'Liability' => 'Pasiva',
                            'Equity' => 'Pasiva',
                            default => ucfirst($state),
                        };
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Asset' => 'danger',
                        'Liability' => 'success',
                        'Equity' => 'success',
                    }),
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
                        if ($record->code === '3-310') {
                            $calculated_balance += self::calculateRevenue();
                        }
                        return 'Rp ' . number_format($calculated_balance / 100, 2);
                    })
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
                        $year = $data['value'] ?? date('Y');

                        // $query->where(function ($query) use ($year) {
                        //     $query->whereYear('cash_flows.transaction_date', '<=', $year)
                        //         ->orWhereNull('cash_flows.transaction_date');
                        // });

                        return $query;
                    })->selectablePlaceholder(false),
            ], layout: FiltersLayout::AboveContent)
            ->defaultGroup('accountType')
            ->header(function () {
                return view('partials.reload-notification');
            })
            ->striped()
            ->poll('30s');
    }

    public static function getEloquentQuery(): Builder
    {

        $year = request('tableFilters.year.value', date('Y'));
        
        $teamId = auth()->user()->teams()->first()->id;

        return parent::getEloquentQuery()
            ->leftJoin('cash_flows', function ($join) use ($teamId, $year) {
                $join->on('accounts.id', '=', 'cash_flows.account_id')
                    ->where('cash_flows.team_id', '=', $teamId)
                    ->whereYear('cash_flows.transaction_date', '<=', $year);
            })
            ->select([
                'accounts.id',
                'accounts.code',
                'accounts.accountName',
                'accounts.accountType',
                'accounts.asset_type',
                DB::raw("
                CASE 
                    WHEN accounts.accountType = 'Asset' THEN 
                        SUM(CASE WHEN cash_flows.type = 'debit' THEN cash_flows.amount ELSE 0 END) - 
                        SUM(CASE WHEN cash_flows.type = 'credit' THEN cash_flows.amount ELSE 0 END)
                    WHEN accounts.accountType IN ('Liability', 'Equity') THEN 
                        SUM(CASE WHEN cash_flows.type = 'credit' THEN cash_flows.amount ELSE 0 END) - 
                        SUM(CASE WHEN cash_flows.type = 'debit' THEN cash_flows.amount ELSE 0 END)
                    ELSE 0
                END as calculated_balance
            ")
            ])
            ->where('accounts.team_id', $teamId)
            ->whereIn('accounts.accountType', ['Asset', 'Liability', 'Equity'])
            ->groupBy(
                'accounts.id',
                'accounts.code',
                'accounts.accountName',
                'accounts.accountType',
                'accounts.asset_type'
            )
            ->orderBy('accounts.code');
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
