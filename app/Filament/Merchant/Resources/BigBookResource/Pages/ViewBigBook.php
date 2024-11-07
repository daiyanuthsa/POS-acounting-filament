<?php

namespace App\Filament\Merchant\Resources\BigBookResource\Pages;

use App\Filament\Merchant\Resources\BigBookResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBigBook extends ViewRecord
{
    protected static string $resource = BigBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
