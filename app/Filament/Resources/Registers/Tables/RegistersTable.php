<?php

namespace App\Filament\Resources\Registers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegistersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('register_type_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('number')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('hijri_year')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gregorian_year')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('page_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('entries_per_page')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('first_entry_serial_in_register')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('last_entry_serial_in_register')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('assigned_notary_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('owner_type')
                    ->badge(),
                TextColumn::make('owner_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('opening_minutes_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('closing_minutes_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_by_user_id')
                    ->numeric()
                    ->sortable(),
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
