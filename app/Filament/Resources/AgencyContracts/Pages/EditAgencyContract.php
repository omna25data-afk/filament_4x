<?php

namespace App\Filament\Resources\AgencyContracts\Pages;

use App\Filament\Resources\AgencyContracts\AgencyContractResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAgencyContract extends EditRecord
{
    protected static string $resource = AgencyContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
