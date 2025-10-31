<?php

namespace App\Filament\Resources\MarriageContracts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MarriageContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('entry_id')
                    ->required()
                    ->numeric(),
                TextInput::make('husband_name')
                    ->required(),
                TextInput::make('wife_name')
                    ->required(),
                TextInput::make('wife_age_at_marriage')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
