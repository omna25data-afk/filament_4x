<?php

namespace App\Filament\Resources\PartitionContracts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PartitionContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('entry_id')
                    ->required()
                    ->numeric(),
                TextInput::make('deceased_name')
                    ->required(),
                Textarea::make('heirs_details')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
