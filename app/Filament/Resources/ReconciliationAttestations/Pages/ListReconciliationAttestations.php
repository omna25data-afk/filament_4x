<?php

namespace App\Filament\Resources\ReconciliationAttestations\Pages;

use App\Filament\Resources\ReconciliationAttestations\ReconciliationAttestationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReconciliationAttestations extends ListRecords
{
    protected static string $resource = ReconciliationAttestationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
