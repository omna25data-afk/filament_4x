<?php

namespace App\Filament\Resources\Entries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('register_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('contract_type_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('writer_type_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('writer_user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('writer_notary_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('writer_other_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('document_hijri_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('document_gregorian_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('document_paper_number')
                    ->searchable(),
                TextColumn::make('entry_status')
                    ->badge(),
                TextColumn::make('certifier_user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('certification_hijri_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('certification_gregorian_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('court_register_entry_number')
                    ->searchable(),
                TextColumn::make('court_register_page_number')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('court_register_number')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('court_box_number')
                    ->searchable(),
                TextColumn::make('delivery_hijri_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('delivery_gregorian_date')
                    ->date()
                    ->sortable(),
                ImageColumn::make('delivery_receipt_image_path'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
