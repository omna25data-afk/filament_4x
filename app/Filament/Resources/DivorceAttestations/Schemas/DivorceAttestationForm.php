<?php

namespace App\Filament\Resources\DivorceAttestations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DivorceAttestationForm
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
            ]);
    }
}
