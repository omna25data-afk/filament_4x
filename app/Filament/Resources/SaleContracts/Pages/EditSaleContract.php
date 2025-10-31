<?php

namespace App\Filament\Resources\SaleContracts\Pages;

use App\Filament\Resources\SaleContracts\SaleContractResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSaleContract extends EditRecord
{
    protected static string $resource = SaleContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
