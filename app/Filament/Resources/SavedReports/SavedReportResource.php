<?php

namespace App\Filament\Resources\SavedReports;

use App\Filament\Resources\SavedReports\Pages\CreateSavedReport;
use App\Filament\Resources\SavedReports\Pages\EditSavedReport;
use App\Filament\Resources\SavedReports\Pages\ListSavedReports;
use App\Filament\Resources\SavedReports\Schemas\SavedReportForm;
use App\Filament\Resources\SavedReports\Tables\SavedReportsTable;
use App\Models\SavedReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SavedReportResource extends Resource
{
    protected static ?string $model = SavedReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SavedReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SavedReportsTable::configure($table);
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
            'index' => ListSavedReports::route('/'),
            'create' => CreateSavedReport::route('/create'),
            'edit' => EditSavedReport::route('/{record}/edit'),
        ];
    }
}
