<?php

namespace App\Filament\Resources\DisposalContracts;

use App\Filament\Resources\DisposalContracts\Pages\CreateDisposalContract;
use App\Filament\Resources\DisposalContracts\Pages\EditDisposalContract;
use App\Filament\Resources\DisposalContracts\Pages\ListDisposalContracts;
use App\Filament\Resources\DisposalContracts\Schemas\DisposalContractForm;
use App\Filament\Resources\DisposalContracts\Tables\DisposalContractsTable;
use App\Models\DisposalContract;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DisposalContractResource extends Resource
{
    protected static ?string $model = DisposalContract::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DisposalContractForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DisposalContractsTable::configure($table);
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
            'index' => ListDisposalContracts::route('/'),
            'create' => CreateDisposalContract::route('/create'),
            'edit' => EditDisposalContract::route('/{record}/edit'),
        ];
    }
}
