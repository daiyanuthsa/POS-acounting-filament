<?php

namespace App\Filament\Merchant\Widgets;

use App\Models\Order;
use Auth;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Carbon\Carbon;
class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Pesanan';

    protected function getData(): array
    {
        $teamId = Auth::user()->teams()->first()->id;
        $data = Trend::query(
            Order::query()->where('team_id', $teamId)
        )
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        $bulan = [
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'Mei',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Agu',
            '09' => 'Sep',
            '10' => 'Okt',
            '11' => 'Nov',
            '12' => 'Des',
        ];


        return [
            'datasets' => [
                [
                    'label' => 'Pesanan',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $bulan[Carbon::parse($value->date)->format('m')]),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
