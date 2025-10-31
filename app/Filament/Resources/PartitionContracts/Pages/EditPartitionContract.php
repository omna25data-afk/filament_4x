<?php

namespace App\Filament\Resources\PartitionContracts\Pages;

use App\Filament\Resources\PartitionContracts\PartitionContractResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPartitionContract extends EditRecord
{
    protected static string $resource = PartitionContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
