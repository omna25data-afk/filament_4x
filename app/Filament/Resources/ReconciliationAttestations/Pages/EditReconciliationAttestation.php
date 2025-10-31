<?php

namespace App\Filament\Resources\ReconciliationAttestations\Pages;

use App\Filament\Resources\ReconciliationAttestations\ReconciliationAttestationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReconciliationAttestation extends EditRecord
{
    protected static string $resource = ReconciliationAttestationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
