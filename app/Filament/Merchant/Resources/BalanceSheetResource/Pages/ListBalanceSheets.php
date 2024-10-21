<?php

namespace App\Filament\Merchant\Resources\BalanceSheetResource\Pages;

use App\Filament\Merchant\Resources\BalanceSheetResource;
use Filament\Actions;

use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListBalanceSheets extends ListRecords
{
    protected static string $resource = BalanceSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
    public function getTabs(): array
    {
        return [
            'Aktiva' => Tab::make('Aktiva')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('accountType', 'Asset');
                }),
            'Pasiva' => Tab::make('Pasiva')
                ->modifyQueryUsing(function ($query) {
                    return $query->whereIn('accountType', ['Liability', 'Equity']);
                }),
        ];
    }
}
