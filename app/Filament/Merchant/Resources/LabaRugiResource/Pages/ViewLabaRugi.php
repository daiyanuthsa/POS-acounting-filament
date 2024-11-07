<?php

namespace App\Filament\Merchant\Resources\LabaRugiResource\Pages;

use App\Filament\Merchant\Resources\LabaRugiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLabaRugi extends ViewRecord
{
    protected static string $resource = LabaRugiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
