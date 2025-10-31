<?php

namespace App\Filament\Resources\MarriageContracts;

use App\Filament\Resources\MarriageContracts\Pages\CreateMarriageContract;
use App\Filament\Resources\MarriageContracts\Pages\EditMarriageContract;
use App\Filament\Resources\MarriageContracts\Pages\ListMarriageContracts;
use App\Filament\Resources\MarriageContracts\Schemas\MarriageContractForm;
use App\Filament\Resources\MarriageContracts\Tables\MarriageContractsTable;
use App\Models\MarriageContract;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MarriageContractResource extends Resource
{
    protected static ?string $model = MarriageContract::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'قيود عقود الزواج';

    protected static ?string $modelLabel = 'قيد عقد زواج';

    protected static ?string $pluralModelLabel = 'قيود عقود الزواج';

    protected static ?string $navigationGroup = 'إدارة القيود';

    public static function form(Schema $schema): Schema
    {
        return MarriageContractForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MarriageContractsTable::configure($table);
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
            'index' => ListMarriageContracts::route('/'),
            'create' => CreateMarriageContract::route('/create'),
            'edit' => EditMarriageContract::route('/{record}/edit'),
        ];
    }
}
