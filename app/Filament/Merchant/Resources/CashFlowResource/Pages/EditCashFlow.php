<?php

namespace App\Filament\Merchant\Resources\CashFlowResource\Pages;

use App\Filament\Merchant\Resources\CashFlowResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCashFlow extends EditRecord
{
    protected static string $resource = CashFlowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
