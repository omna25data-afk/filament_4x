<?php

namespace App\Filament\Resources\AgencyContracts;

use App\Filament\Resources\AgencyContracts\Pages\CreateAgencyContract;
use App\Filament\Resources\AgencyContracts\Pages\EditAgencyContract;
use App\Filament\Resources\AgencyContracts\Pages\ListAgencyContracts;
use App\Filament\Resources\AgencyContracts\Schemas\AgencyContractForm;
use App\Filament\Resources\AgencyContracts\Tables\AgencyContractsTable;
use App\Models\AgencyContract;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AgencyContractResource extends Resource
{
    protected static ?string $model = AgencyContract::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'قيود عقود الوكالات';

    protected static ?string $modelLabel = 'قيد عقد وكالة';

    protected static ?string $pluralModelLabel = 'قيود عقود الوكالات';

    protected static ?string $navigationGroup = 'إدارة القيود';

    public static function form(Schema $schema): Schema
    {
        return AgencyContractForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgencyContractsTable::configure($table);
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
            'index' => ListAgencyContracts::route('/'),
            'create' => CreateAgencyContract::route('/create'),
            'edit' => EditAgencyContract::route('/{record}/edit'),
        ];
    }
}
