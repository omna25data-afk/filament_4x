<?php

namespace App\Filament\Resources\DivorceAttestations\Pages;

use App\Filament\Resources\DivorceAttestations\DivorceAttestationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDivorceAttestation extends EditRecord
{
    protected static string $resource = DivorceAttestationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
