<?php

namespace App\Filament\Merchant\Resources\StockMovementResource\Pages;

use App\Filament\Merchant\Resources\StockMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStockMovements extends ManageRecords
{
    protected static string $resource = StockMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
