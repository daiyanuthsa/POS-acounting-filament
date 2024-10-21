<?php

namespace App\Filament\Merchant\Resources\EquityStatementResource\Pages;

use App\Filament\Merchant\Resources\EquityStatementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEquityStatement extends EditRecord
{
    protected static string $resource = EquityStatementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
