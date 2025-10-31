<?php

namespace App\Filament\Resources\MarriageContracts\Pages;

use App\Filament\Resources\MarriageContracts\MarriageContractResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMarriageContracts extends ListRecords
{
    protected static string $resource = MarriageContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
