<?php

namespace App\Filament\Merchant\Resources\BigBookResource\Widgets;


use App\Models\BigBook;
use Auth;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DebitCreditWidget extends BaseWidget
{
    public ?array $filters = [];
    // protected int|string|array $columnSpan = 'full';
    // protected int|string|array $columnSpan = [
    //   'md' => 2,
    //     'xl' => 2,  
    // ];

    #[On('filterApplied')]
    public function updateFilters($filters): void
    {
        $this->filters = $filters;
    }

    protected function getStats(): array
    {
        $team_id = Auth::user()->teams()->first()->id;

        $currentDebitTotal = $this->getCurrentPeriodTotal('debit', $team_id);
        $currentCreditTotal = $this->getCurrentPeriodTotal('credit', $team_id);
        $previousDebitTotal = $this->getPreviousPeriodTotal('debit', $team_id);
        $previousCreditTotal = $this->getPreviousPeriodTotal('credit', $team_id);

        $debitPercentageChange = $this->calculatePercentageChange($currentDebitTotal, $previousDebitTotal);
        $creditPercentageChange = $this->calculatePercentageChange($currentCreditTotal, $previousCreditTotal);

        return [
            $this->createStat('Total Debit', $currentDebitTotal, $debitPercentageChange),
            $this->createStat('Total Credit', $currentCreditTotal, $creditPercentageChange),
        ];
    }

    protected function createStat(string $label, float $total, float $percentageChange): Stat
    {
        return Stat::make($label, 'IDR ' . number_format($total / 100, 2))
            ->description(sprintf('%.2f%% %s than previous period', abs($percentageChange), $percentageChange >= 0 ? 'higher' : 'lower'))
            ->descriptionIcon($percentageChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($percentageChange >= 0 ? 'success' : 'danger')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->extraAttributes([
                'class' => 'cursor-pointer',
            ]);
    }

    protected function calculatePercentageChange(float $current, float $previous): float
    {
        return $previous > 0 ? (($current - $previous) / $previous) * 100 : 0;
    }

    protected function getCurrentPeriodTotal(string $column, int $teamId): float
    {
        return $this->applyFilters(BigBook::query()->where('team_id', $teamId))->sum($column);
    }

    protected function getPreviousPeriodTotal(string $column, int $teamId): float
    {
        $query = BigBook::query()->where('team_id', $teamId);

        Log::info('Filters:', ['filters' => $this->filters]);

        if (!empty($this->filters)) {
            $month = $this->getFilterValue('transaction_month');
            $year = $this->getFilterValue('transaction_year');

            Log::info('Extracted filter values:', ['month' => $month, 'year' => $year]);

            if ($month !== null && $year !== null) {
                try {
                    $date = Carbon::createFromDate($year, $month, 1);
                    $previousDate = $date->copy()->subMonth();

                    $query->whereMonth('transaction_date', $previousDate->month)
                        ->whereYear('transaction_date', $previousDate->year);
                } catch (\Exception $e) {
                    Log::error('Error in date calculation', ['error' => $e->getMessage()]);
                }
            } elseif ($year !== null) {
                $query->whereYear('transaction_date', intval($year) - 1);
            }
        } else {
            // Jika tidak ada filter, bandingkan dengan tahun sebelumnya
            $query->whereYear('transaction_date', now()->subYear()->year);
        }

        $total = $query->sum($column);
        Log::info('Query result:', ['total' => $total]);

        return $total;
    }

    protected function getFilterValue($key)
    {
        if (!isset($this->filters[$key])) {
            Log::info("Filter key not found: {$key}");
            return null;
        }

        $value = $this->filters[$key];
        Log::info("Raw filter value for {$key}:", ['value' => $value]);

        if (is_array($value)) {
            if (empty($value)) {
                Log::info("Empty array for filter: {$key}");
                return null;
            }
            if (isset($value[0])) {
                Log::info("Returning first element of array for {$key}: {$value[0]}");
                return $value[0];
            }
            Log::info("Array does not have a 0 index for {$key}", ['array' => $value]);
            return null;
        }

        Log::info("Returning non-array value for {$key}: {$value}");
        return $value;
    }

    protected function applyFilters(Builder $query): Builder
    {
        return $query
            ->when(
                $this->filters['transaction_month'] ?? null,
                fn(Builder $query, $month): Builder => $query->whereMonth('transaction_date', $month)
            )
            ->when(
                $this->filters['transaction_year'] ?? null,
                fn(Builder $query, $year): Builder => $query->whereYear('transaction_date', $year)
            );
    }
}
