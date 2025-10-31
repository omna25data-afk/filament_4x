<?php

namespace App\Filament\Resources\SaleContracts\Pages;

use App\Filament\Resources\SaleContracts\SaleContractResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSaleContracts extends ListRecords
{
    protected static string $resource = SaleContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
