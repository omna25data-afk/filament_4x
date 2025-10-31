<?php

namespace App\Filament\Resources\Notaries;

use App\Filament\Resources\Notaries\Pages\CreateNotary;
use App\Filament\Resources\Notaries\Pages\EditNotary;
use App\Filament\Resources\Notaries\Pages\ListNotaries;
use App\Filament\Resources\Notaries\Schemas\NotaryForm;
use App\Filament\Resources\Notaries\Tables\NotariesTable;
use App\Models\Notary;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NotaryResource extends Resource
{
    protected static ?string $model = Notary::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return NotaryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotariesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNotaries::route('/'),
            'create' => CreateNotary::route('/create'),
            'edit' => EditNotary::route('/{record}/edit'),
        ];
    }
}
