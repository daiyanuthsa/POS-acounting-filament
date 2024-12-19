<?php

namespace App\Filament\Merchant\Resources\EquityStatementResource\Pages;

use App\Filament\Merchant\Resources\EquityStatementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEquityStatements extends ListRecords
{
    protected static string $resource = EquityStatementResource::class;

    protected function getHeaderActions(): array
    {
        $decodequerystring = urldecode(request()->getQueryString());
        return [
            Actions\Action::make('export')
                ->label('Cetak Laporan')
                ->url(url('/equitystatement-report?' . $decodequerystring))
                ->openUrlInNewTab()
        ];
    }
}
