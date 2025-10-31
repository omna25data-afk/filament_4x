<?php

namespace App\Filament\Resources\PartitionContracts\Pages;

use App\Filament\Resources\PartitionContracts\PartitionContractResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPartitionContracts extends ListRecords
{
    protected static string $resource = PartitionContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
