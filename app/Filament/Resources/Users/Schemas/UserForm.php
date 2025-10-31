<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->label('اسم المستخدم')
                    ->required(),
                TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->required(),
                Select::make('role')
                    ->label('الدور')
                    ->options([
                        'admin' => 'مدير نظام', 
                        'notary' => 'كاتب عدل', 
                        'assistant_admin' => 'مساعد مدير'
                    ])
                    ->default('notary')
                    ->required(),
                TextInput::make('full_name_ar')
                    ->label('الاسم الكامل')
                    ->required(),
                TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->default(null),
                TextInput::make('phone_number')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->default(null),
                TextInput::make('profile_picture_path')
                    ->label('مسار الصورة الشخصية')
                    ->default(null),
                Toggle::make('is_active')
                    ->label('نشط')
                    ->required(),
                DateTimePicker::make('last_login_at')
                    ->label('آخر دخول'),
                DateTimePicker::make('email_verified_at')
                    ->label('تأكيد البريد'),
            ]);
    }
}
