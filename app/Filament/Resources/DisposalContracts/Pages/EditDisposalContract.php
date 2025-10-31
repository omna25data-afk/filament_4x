<?php

namespace App\Filament\Resources\DisposalContracts\Pages;

use App\Filament\Resources\DisposalContracts\DisposalContractResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDisposalContract extends EditRecord
{
    protected static string $resource = DisposalContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
