<?php

namespace App\Filament\Resources\Notaries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NotariesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('first_name_ar')
                    ->searchable(),
                TextColumn::make('second_name_ar')
                    ->searchable(),
                TextColumn::make('third_name_ar')
                    ->searchable(),
                TextColumn::make('fourth_name_ar')
                    ->searchable(),
                TextColumn::make('birth_place_governorate_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('birth_place_directorate_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('birth_place_sub_district_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('birth_place_village_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('birth_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('home_phone')
                    ->searchable(),
                TextColumn::make('qualification')
                    ->searchable(),
                TextColumn::make('job')
                    ->searchable(),
                TextColumn::make('workplace')
                    ->searchable(),
                TextColumn::make('functional_status')
                    ->badge(),
                TextColumn::make('stop_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('م')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('الاسم الأول')
                    ->searchable(),
                TextColumn::make('الإسم الثاني')
                    ->searchable(),
                TextColumn::make('الإسم الثالث')
                    ->searchable(),
                TextColumn::make('الإسم الرابع')
                    ->searchable(),
                TextColumn::make('اللقب')
                    ->searchable(),
                TextColumn::make('محل الميلاد')
                    ->searchable(),
                TextColumn::make('تاريخ الميلاد')
                    ->searchable(),
                TextColumn::make('نوع الهوية')
                    ->searchable(),
                TextColumn::make('رقم الهوية')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('تاريخ الإصدار')
                    ->searchable(),
                TextColumn::make('جهة الإصدار')
                    ->searchable(),
                TextColumn::make('العمل')
                    ->searchable(),
                TextColumn::make('جهة العمل')
                    ->searchable(),
                TextColumn::make('المؤهل')
                    ->searchable(),
                TextColumn::make('العنوان')
                    ->searchable(),
                TextColumn::make('قرى مناطق الإختصاص')
                    ->searchable(),
                TextColumn::make('عزلة مناطق الإختصاص')
                    ->searchable(),
                TextColumn::make('رقم التلفون')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('رقم القرار الوزاري')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('تاريخ القرار')
                    ->searchable(),
                TextColumn::make('رقم بطاقة الترخيص')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('تاريخ الترخيص')
                    ->searchable(),
                TextColumn::make('تاريخ إنتهاء أول ترخيص')
                    ->searchable(),
                TextColumn::make('رقم البطاقة الإلكترونية')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('تاريخ إصدار البطاقة')
                    ->searchable(),
                TextColumn::make('تاريخ إنتهاء أول بطاقة')
                    ->searchable(),
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
