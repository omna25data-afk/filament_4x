<?php

namespace App\Filament\Resources\SaleContracts;

use App\Filament\Resources\SaleContracts\Pages\CreateSaleContract;
use App\Filament\Resources\SaleContracts\Pages\EditSaleContract;
use App\Filament\Resources\SaleContracts\Pages\ListSaleContracts;
use App\Filament\Resources\SaleContracts\Schemas\SaleContractForm;
use App\Filament\Resources\SaleContracts\Tables\SaleContractsTable;
use App\Models\SaleContract;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SaleContractResource extends Resource
{
    protected static ?string $model = SaleContract::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'قيود عقود المبيعات';

    protected static ?string $modelLabel = 'قيد عقد بيع';

    protected static ?string $pluralModelLabel = 'قيود عقود المبيعات';

    protected static UnitEnum|string|null $navigationGroup = 'إدارة القيود';

    public static function form(Schema $schema): Schema
    {
        return SaleContractForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SaleContractsTable::configure($table);
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
            'index' => ListSaleContracts::route('/'),
            'create' => CreateSaleContract::route('/create'),
            'edit' => EditSaleContract::route('/{record}/edit'),
        ];
    }
}
