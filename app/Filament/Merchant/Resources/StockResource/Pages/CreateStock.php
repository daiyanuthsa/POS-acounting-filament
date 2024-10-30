<?php

namespace App\Filament\Merchant\Resources\StockResource\Pages;

use App\Filament\Merchant\Resources\StockResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\HtmlString;

class CreateStock extends CreateRecord
{
    protected static string $resource = StockResource::class;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->submit(null)
            ->requiresConfirmation()
            ->modalHeading('Tambah Stok Produk')
            ->modalDescription(new HtmlString('Apakah data yang dimasukan benar?<br/>Data yang sudah di input tidak dapat diubah'))
            ->action(function () {
                $this->closeActionModal();
                $this->create();
            });
    }
}
