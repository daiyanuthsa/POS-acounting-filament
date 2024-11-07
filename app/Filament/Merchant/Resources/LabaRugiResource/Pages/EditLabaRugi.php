<?php

namespace App\Filament\Merchant\Resources\LabaRugiResource\Pages;

use App\Filament\Merchant\Resources\LabaRugiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLabaRugi extends EditRecord
{
    protected static string $resource = LabaRugiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
