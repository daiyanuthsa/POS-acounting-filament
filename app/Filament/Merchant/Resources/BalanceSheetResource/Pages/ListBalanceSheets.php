<?php

namespace App\Filament\Merchant\Resources\BalanceSheetResource\Pages;

use App\Filament\Merchant\Resources\BalanceSheetResource;
use App\Filament\Merchant\Resources\BalanceSheetResource\Widgets\AssetsTotal;
use App\Filament\Merchant\Resources\BalanceSheetResource\Widgets\PassivaTotal;
use Filament\Actions;

use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
class ListBalanceSheets extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = BalanceSheetResource::class;

    protected function getHeaderActions(): array
    {
        $decodequerystring = urldecode(request()->getQueryString());
        return [
            Actions\Action::make('export')
                ->label('Cetak Laporan')
                ->url(url('/report-balancesheet?' . $decodequerystring))
                ->openUrlInNewTab(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            AssetsTotal::class,
        ];
    }
}
