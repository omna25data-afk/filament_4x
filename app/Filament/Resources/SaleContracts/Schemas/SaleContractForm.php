<?php

namespace App\Filament\Resources\SaleContracts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SaleContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('entry_id')
                    ->required()
                    ->numeric(),
                Select::make('sale_subtype')
                    ->options(['movable' => 'Movable', 'immovable' => 'Immovable'])
                    ->required(),
                TextInput::make('seller_name')
                    ->required(),
                TextInput::make('buyer_name')
                    ->required(),
                Textarea::make('item_description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('item_value')
                    ->required()
                    ->numeric(),
            ]);
    }
}
