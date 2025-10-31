<?php

namespace App\Filament\Resources\Notifications\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NotificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Select::make('type')
                    ->options(['info' => 'Info', 'success' => 'Success', 'warning' => 'Warning', 'danger' => 'Danger'])
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_read')
                    ->required(),
                TextInput::make('related_entry_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('related_notary_id')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
