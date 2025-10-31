<?php

namespace App\Filament\Resources\DisposalContracts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DisposalContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('entry_id')
                    ->required()
                    ->numeric(),
                TextInput::make('disposal_subtype_id')
                    ->required()
                    ->numeric(),
                TextInput::make('disposer_name')
                    ->required(),
                TextInput::make('disposer_for_name')
                    ->required(),
            ]);
    }
}
