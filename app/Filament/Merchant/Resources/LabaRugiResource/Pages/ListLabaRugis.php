<?php

namespace App\Filament\Merchant\Resources\LabaRugiResource\Pages;

use App\Filament\Merchant\Resources\LabaRugiResource;
use App\Filament\Merchant\Resources\LabaRugiResource\Widgets\LabaRugiWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListLabaRugis extends ListRecords
{
    protected static string $resource = LabaRugiResource::class;

    protected function getHeaderActions(): array
    {
        $decodequerystring = urldecode(request()->getQueryString());
        return [
            Actions\Action::make('export')
                ->label('Cetak Laporan')
                ->url(url('/profitloss-report?' . $decodequerystring))
                ->openUrlInNewTab()
        ];
    }

    public function getTabs(): array
    {
        return [
            'Pendapatan' => Tab::make('Pendapatan')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('type', 'Revenue');
                }),
            'Pengeluaran' => Tab::make('Pengeluaran')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('type', 'Expense');
                }),
            'HPP' => Tab::make('HPP')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('type', 'UPC');
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LabaRugiWidget::class,
        ];
    }
}
