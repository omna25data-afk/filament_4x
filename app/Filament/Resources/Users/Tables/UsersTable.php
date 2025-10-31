<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->label('اسم المستخدم')
                    ->searchable(),
                TextColumn::make('role')
                    ->label('الدور')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'رئيس قلم التوثيق',
                        'notary' => 'الأمين الشرعي',
                        'assistant_admin' => 'رئيس وحدة الأمناء',
                        'documentation_writer' => 'كاتب توثيق',
                        'data_entry' => 'مدخل بيانات',
                        'treasury_guardian' => 'أمين صندوق',
                        default => $state,
                    }),
                TextColumn::make('full_name_ar')
                    ->label('الاسم الكامل')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->label('رقم الهاتف')
                    ->searchable(),
                TextColumn::make('profile_picture_path')
                    ->label('مسار الصورة الشخصية')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                TextColumn::make('last_login_at')
                    ->label('آخر دخول')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->label('تأكيد البريد')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
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
