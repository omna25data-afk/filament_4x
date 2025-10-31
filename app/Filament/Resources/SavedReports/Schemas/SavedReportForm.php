<?php

namespace App\Filament\Resources\SavedReports\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SavedReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name_ar')
                    ->required(),
                Textarea::make('config_json')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
