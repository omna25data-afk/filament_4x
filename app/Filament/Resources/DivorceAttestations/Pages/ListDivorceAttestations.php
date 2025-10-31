<?php

namespace App\Filament\Resources\DivorceAttestations\Pages;

use App\Filament\Resources\DivorceAttestations\DivorceAttestationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDivorceAttestations extends ListRecords
{
    protected static string $resource = DivorceAttestationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
