<?php

namespace App\Filament\Resources\PartitionContracts;

use App\Filament\Resources\PartitionContracts\Pages\CreatePartitionContract;
use App\Filament\Resources\PartitionContracts\Pages\EditPartitionContract;
use App\Filament\Resources\PartitionContracts\Pages\ListPartitionContracts;
use App\Filament\Resources\PartitionContracts\Schemas\PartitionContractForm;
use App\Filament\Resources\PartitionContracts\Tables\PartitionContractsTable;
use App\Models\PartitionContract;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartitionContractResource extends Resource
{
    protected static ?string $model = PartitionContract::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'قيود فصول القسمة';

    protected static ?string $modelLabel = 'قيد فصل قسمة';

    protected static ?string $pluralModelLabel = 'قيود فصول القسمة';

    protected static UnitEnum|string|null $navigationGroup = 'إدارة القيود';

    public static function form(Schema $schema): Schema
    {
        return PartitionContractForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartitionContractsTable::configure($table);
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
            'index' => ListPartitionContracts::route('/'),
            'create' => CreatePartitionContract::route('/create'),
            'edit' => EditPartitionContract::route('/{record}/edit'),
        ];
    }
}
