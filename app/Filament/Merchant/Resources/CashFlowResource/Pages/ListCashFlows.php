<?php

namespace App\Filament\Merchant\Resources\CashFlowResource\Pages;

use App\Filament\Merchant\Resources\CashFlowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCashFlows extends ListRecords
{
    protected static string $resource = CashFlowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Jurnal'),
        ];
    }
}
