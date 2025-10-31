<?php

namespace App\Filament\Resources\AgencyContracts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class AgencyContractForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('entry_id')
                    ->required()
                    ->numeric(),
                Select::make('agency_subtype')
                    ->options(['new_agency' => 'New agency', 'cancellation_agency' => 'Cancellation agency'])
                    ->required(),
                TextInput::make('principal_name')
                    ->required(),
                TextInput::make('agent_name')
                    ->required(),
            ]);
    }
}
