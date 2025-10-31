<?php

namespace App\Filament\Resources\Notaries\Pages;

use App\Filament\Resources\Notaries\NotaryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNotary extends EditRecord
{
    protected static string $resource = NotaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
