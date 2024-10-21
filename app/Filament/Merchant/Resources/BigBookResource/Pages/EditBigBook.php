<?php

namespace App\Filament\Merchant\Resources\BigBookResource\Pages;

use App\Filament\Merchant\Resources\BigBookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBigBook extends EditRecord
{
    protected static string $resource = BigBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
