<?php

namespace App\Filament\Resources\Registers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RegisterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('register_type_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('number')
                    ->required()
                    ->numeric(),
                TextInput::make('hijri_year')
                    ->required()
                    ->numeric(),
                TextInput::make('gregorian_year')
                    ->numeric()
                    ->default(null),
                TextInput::make('page_count')
                    ->required()
                    ->numeric(),
                TextInput::make('entries_per_page')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('first_entry_serial_in_register')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('last_entry_serial_in_register')
                    ->numeric()
                    ->default(null),
                TextInput::make('assigned_notary_id')
                    ->numeric()
                    ->default(null),
                Select::make('owner_type')
                    ->options(['admin' => 'Admin', 'notary' => 'Notary'])
                    ->required(),
                TextInput::make('owner_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('opening_minutes_date'),
                DatePicker::make('closing_minutes_date'),
                Select::make('status')
                    ->options(['active' => 'Active', 'completed' => 'Completed', 'archived' => 'Archived'])
                    ->default('active')
                    ->required(),
                TextInput::make('created_by_user_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
