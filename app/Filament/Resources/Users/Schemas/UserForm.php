<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('role')
                    ->options(['admin' => 'Admin', 'notary' => 'Notary', 'assistant_admin' => 'Assistant admin'])
                    ->default('notary')
                    ->required(),
                TextInput::make('full_name_ar')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                TextInput::make('phone_number')
                    ->tel()
                    ->default(null),
                TextInput::make('profile_picture_path')
                    ->default(null),
                Toggle::make('is_active')
                    ->required(),
                DateTimePicker::make('last_login_at'),
                DateTimePicker::make('email_verified_at'),
            ]);
    }
}
