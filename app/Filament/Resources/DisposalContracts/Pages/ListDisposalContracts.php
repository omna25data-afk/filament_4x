<?php

namespace App\Filament\Resources\DisposalContracts\Pages;

use App\Filament\Resources\DisposalContracts\DisposalContractResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDisposalContracts extends ListRecords
{
    protected static string $resource = DisposalContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
