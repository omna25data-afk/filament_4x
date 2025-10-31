<?php

namespace App\Filament\Resources\MarriageContracts\Pages;

use App\Filament\Resources\MarriageContracts\MarriageContractResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMarriageContract extends EditRecord
{
    protected static string $resource = MarriageContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
