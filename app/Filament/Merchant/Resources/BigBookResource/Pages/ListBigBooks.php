<?php

namespace App\Filament\Merchant\Resources\BigBookResource\Pages;

use App\Filament\Merchant\Resources\BigBookResource;
use App\Filament\Merchant\Resources\BigBookResource\Widgets\DebitCreditWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Form;
use Filament\Resources\Pages\ListRecords\Concerns\Filterable;
class ListBigBooks extends ListRecords
{
    protected static string $resource = BigBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            DebitCreditWidget::class,
        ];
    }

    public function getTableFiltersForm(): Form
    {
        return parent::getTableFiltersForm()
            ->statePath('tableFilters')
            ->reactive()
            ->live();
    }

    public function updatedTableFilters(): void
    {
        $this->dispatch('filterApplied', filters: $this->getTableFiltersForm()->getState());
    }

    
}
