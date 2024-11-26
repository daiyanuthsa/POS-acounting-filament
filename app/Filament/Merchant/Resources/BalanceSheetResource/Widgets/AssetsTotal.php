<?php

namespace App\Filament\Merchant\Resources\BalanceSheetResource\Widgets;

use App\Filament\Merchant\Resources\BalanceSheetResource;
use App\Filament\Merchant\Resources\BalanceSheetResource\Pages\ListBalanceSheets;
use App\Models\Account;
use App\Models\LabaRugi;
use DB;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AssetsTotal extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListBalanceSheets::class;
    }
    protected function getStats(): array
    {
        $components = request('components', []);
        $snapshot = $components[0]['snapshot'] ?? null;

        if ($snapshot) {
            $decodedSnapshot = json_decode($snapshot, true);
            $tableFilters = $decodedSnapshot['data']['tableFilters'] ?? [];
        } else {
            $tableFilters = [];
        }

        // Ambil tahun dari tableFilters
        $year = collect($tableFilters)
            ->firstWhere('year')['year'][0]['value'] ?? date('Y');



        $teamId = auth()->user()->teams()->first()->id;

        // Hitung Total Aktiva (Asset)
        $totalAktiva = Account::leftJoin('cash_flows', function ($join) use ($year, $teamId) {
            

            $join->on('accounts.id', '=', 'cash_flows.account_id')
                ->where('cash_flows.team_id', '=', $teamId)
                ->whereYear('cash_flows.transaction_date', '<=', $year);
        })
            ->selectRaw("
                CASE 
                    WHEN accounts.accountType = 'Asset' THEN 
                        SUM(CASE WHEN cash_flows.type = 'debit' THEN cash_flows.amount ELSE 0 END) - 
                        SUM(CASE WHEN cash_flows.type = 'credit' THEN cash_flows.amount ELSE 0 END)
                    ELSE 0
                END as calculated_balance
            ")
            ->where('accounts.team_id', $teamId)
            ->where('accounts.accountType', 'Asset')
            ->groupBy('accounts.accountType')
            ->value('calculated_balance');



        // Hitung Total Pasiva (Liability + Equity)
        $totalPasiva = Account::leftJoin('cash_flows', function ($join) use ($teamId, $year) {
            $join->on('accounts.id', '=', 'cash_flows.account_id')
                ->where('cash_flows.team_id', '=', $teamId)
                ->whereYear('cash_flows.transaction_date', '<=', $year);
        })
            ->where('accounts.team_id', $teamId)
            ->whereIn('accounts.accountType', ['Liability', 'Equity'])
            ->selectRaw("
            SUM(
                CASE WHEN cash_flows.type = 'credit' THEN cash_flows.amount 
                ELSE 0 
                END - 
                CASE WHEN cash_flows.type = 'debit' THEN cash_flows.amount 
                ELSE 0 
                END
            ) as total_pasiva
        ")
            ->value('total_pasiva') ?? 0;
        // dd($totalPasiva);

        // Tambahkan perhitungan revenue untuk penyesuaian
        $revenue = self::calculateRevenueByYear($year);

        return [
            Stat::make('Total Aktiva', 'Rp ' . number_format(abs($totalAktiva) / 100, 2))
                ->description('Total Aset Perusahaan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Pasiva', 'Rp ' . number_format(abs($totalPasiva + $revenue) / 100, 2))
                ->description('Total Liabilitas & Ekuitas')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
        ];
    }
    public static function calculateRevenueByYear(int $year): float
    {
        $teamId = auth()->user()->teams()->first()->id; // Ambil ID tim dari pengguna yang sedang login

        // Query untuk menghitung total pendapatan, pengeluaran, dan HPP
        $totals = LabaRugi::query()
            ->where('team_id', $teamId)
            ->select('type')
            ->selectRaw('SUM(debit - credit) as total')
            ->whereYear('transaction_date', '<=', $year)
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        // Ambil nilai untuk pendapatan, pengeluaran, dan HPP
        $pendapatan = abs($totals['Revenue'] ?? 0);
        $pengeluaran = abs($totals['Expense'] ?? 0);
        $hpp = abs($totals['UPC'] ?? 0);

        // Hitung revenue
        return $pendapatan - $pengeluaran - $hpp;
    }


}
