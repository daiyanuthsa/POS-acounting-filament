<?php

namespace App\Filament\Merchant\Resources\OrderResource\Pages;

use App\Filament\Merchant\Resources\OrderResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\HtmlString;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Buat Pesanan')
            ->submit(null)
            ->requiresConfirmation()
            ->modalHeading('Buat Pesanan')
            ->modalDescription(new HtmlString('Apakah data yang dimasukan benar?<br/>Data yang sudah di input tidak dapat diubah'))
            ->action(function () {
                $this->closeActionModal();
                $this->create();
            });
    }
}
