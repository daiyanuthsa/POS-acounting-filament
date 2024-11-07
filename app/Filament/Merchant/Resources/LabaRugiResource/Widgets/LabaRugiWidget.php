<?php

namespace App\Filament\Merchant\Resources\LabaRugiResource\Widgets;

use App\Models\LabaRugi;
use Auth;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LabaRugiWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $team_id = Auth::user()->teams()->first()->id;
        $totals = LabaRugi::query()
            ->where('team_id', $team_id)
            ->select('type')
            ->selectRaw('SUM(debit - credit) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        $pendapatan = abs($totals['Revenue'] ?? 0);
        $pengeluaran = abs($totals['Expense'] ?? 0);
        $hpp = abs($totals['UPC'] ?? 0);

        $labaRugi = $pendapatan - $pengeluaran - $hpp;

        $formattedLabaRugi = 'Rp ' . number_format($labaRugi / 100, 2);
        $status = $labaRugi >= 0 ? 'Laba' : 'Rugi';
        $color = $labaRugi >= 0 ? 'success' : 'danger';

        return [
            Stat::make('Pendapatan', 'Rp ' . number_format($pendapatan / 100, 2))
                ->extraAttributes(['class' => 'laba-rugi-stat']),
            Stat::make('Pengeluaran', 'Rp ' . number_format($pengeluaran / 100, 2))
                ->extraAttributes(['class' => 'laba-rugi-stat']),
            Stat::make('HPP', 'Rp ' . number_format($hpp / 100, 2))
                ->extraAttributes(['class' => 'laba-rugi-stat']),
            Stat::make("Total ($status)", $formattedLabaRugi)
                ->color($color)
                ->description("Hasil perhitungan laba/rugi")
                ->descriptionIcon('heroicon-m-calculator')
                ->chart([
                    $pendapatan,
                    -$pengeluaran,
                    -$hpp,
                    $labaRugi
                ])
                ->extraAttributes(['class' => 'text-xl']),
        ];
    }

    public static function getStyles(): string
    {
        return <<<CSS
        .laba-rugi-stat .fi-stats-overview-stat-description {
            font-size: 0.75rem;
        }
        .laba-rugi-stat .fi-stats-overview-stat-value {
            font-size: 1rem;
        }
        CSS;
    }
}
