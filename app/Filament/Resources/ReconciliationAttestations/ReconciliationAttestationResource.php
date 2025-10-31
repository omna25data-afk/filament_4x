<?php

namespace App\Filament\Resources\ReconciliationAttestations;

use App\Filament\Resources\ReconciliationAttestations\Pages\CreateReconciliationAttestation;
use App\Filament\Resources\ReconciliationAttestations\Pages\EditReconciliationAttestation;
use App\Filament\Resources\ReconciliationAttestations\Pages\ListReconciliationAttestations;
use App\Filament\Resources\ReconciliationAttestations\Schemas\ReconciliationAttestationForm;
use App\Filament\Resources\ReconciliationAttestations\Tables\ReconciliationAttestationsTable;
use App\Models\ReconciliationAttestation;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReconciliationAttestationResource extends Resource
{
    protected static ?string $model = ReconciliationAttestation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'قيود إشهادات الرجعة';

    protected static ?string $modelLabel = 'قيد إشهاد رجعة';

    protected static ?string $pluralModelLabel = 'قيود إشهادات الرجعة';

    protected static UnitEnum|string|null $navigationGroup = 'إدارة القيود';

    public static function form(Schema $schema): Schema
    {
        return ReconciliationAttestationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReconciliationAttestationsTable::configure($table);
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
            'index' => ListReconciliationAttestations::route('/'),
            'create' => CreateReconciliationAttestation::route('/create'),
            'edit' => EditReconciliationAttestation::route('/{record}/edit'),
        ];
    }
}
