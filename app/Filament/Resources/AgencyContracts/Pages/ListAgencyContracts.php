<?php

namespace App\Filament\Resources\AgencyContracts\Pages;

use App\Filament\Resources\AgencyContracts\AgencyContractResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAgencyContracts extends ListRecords
{
    protected static string $resource = AgencyContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
