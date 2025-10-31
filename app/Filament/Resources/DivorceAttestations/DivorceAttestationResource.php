<?php

namespace App\Filament\Resources\DivorceAttestations;

use App\Filament\Resources\DivorceAttestations\Pages\CreateDivorceAttestation;
use App\Filament\Resources\DivorceAttestations\Pages\EditDivorceAttestation;
use App\Filament\Resources\DivorceAttestations\Pages\ListDivorceAttestations;
use App\Filament\Resources\DivorceAttestations\Schemas\DivorceAttestationForm;
use App\Filament\Resources\DivorceAttestations\Tables\DivorceAttestationsTable;
use App\Models\DivorceAttestation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DivorceAttestationResource extends Resource
{
    protected static ?string $model = DivorceAttestation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DivorceAttestationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DivorceAttestationsTable::configure($table);
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
            'index' => ListDivorceAttestations::route('/'),
            'create' => CreateDivorceAttestation::route('/create'),
            'edit' => EditDivorceAttestation::route('/{record}/edit'),
        ];
    }
}
