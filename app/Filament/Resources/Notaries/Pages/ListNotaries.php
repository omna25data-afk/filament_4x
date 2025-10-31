<?php

namespace App\Filament\Resources\Notaries\Pages;

use App\Filament\Resources\Notaries\NotaryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNotaries extends ListRecords
{
    protected static string $resource = NotaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
