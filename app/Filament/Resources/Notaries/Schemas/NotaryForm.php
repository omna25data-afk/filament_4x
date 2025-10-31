<?php

namespace App\Filament\Resources\Notaries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class NotaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('first_name_ar')
                    ->required(),
                TextInput::make('second_name_ar')
                    ->required(),
                TextInput::make('third_name_ar')
                    ->required(),
                TextInput::make('fourth_name_ar')
                    ->required(),
                TextInput::make('birth_place_governorate_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('birth_place_directorate_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('birth_place_sub_district_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('birth_place_village_id')
                    ->numeric()
                    ->default(null),
                DatePicker::make('birth_date'),
                TextInput::make('home_phone')
                    ->tel()
                    ->default(null),
                Textarea::make('address')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('qualification')
                    ->default(null),
                TextInput::make('job')
                    ->default(null),
                TextInput::make('workplace')
                    ->default(null),
                Select::make('functional_status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive', 'suspended' => 'Suspended'])
                    ->default('active')
                    ->required(),
                Textarea::make('stop_reason')
                    ->default(null)
                    ->columnSpanFull(),
                DatePicker::make('stop_date'),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('م')
                    ->numeric()
                    ->default(null),
                TextInput::make('الاسم الأول')
                    ->default(null),
                TextInput::make('الإسم الثاني')
                    ->default(null),
                TextInput::make('الإسم الثالث')
                    ->default(null),
                TextInput::make('الإسم الرابع')
                    ->default(null),
                TextInput::make('اللقب')
                    ->default(null),
                TextInput::make('محل الميلاد')
                    ->default(null),
                TextInput::make('تاريخ الميلاد')
                    ->default(null),
                TextInput::make('نوع الهوية')
                    ->default(null),
                TextInput::make('رقم الهوية')
                    ->numeric()
                    ->default(null),
                TextInput::make('تاريخ الإصدار')
                    ->default(null),
                TextInput::make('جهة الإصدار')
                    ->default(null),
                TextInput::make('العمل')
                    ->default(null),
                TextInput::make('جهة العمل')
                    ->default(null),
                TextInput::make('المؤهل')
                    ->default(null),
                TextInput::make('العنوان')
                    ->default(null),
                TextInput::make('قرى مناطق الإختصاص')
                    ->default(null),
                TextInput::make('عزلة مناطق الإختصاص')
                    ->default(null),
                TextInput::make('رقم التلفون')
                    ->numeric()
                    ->default(null),
                TextInput::make('رقم القرار الوزاري')
                    ->numeric()
                    ->default(null),
                TextInput::make('تاريخ القرار')
                    ->default(null),
                TextInput::make('رقم بطاقة الترخيص')
                    ->numeric()
                    ->default(null),
                TextInput::make('تاريخ الترخيص')
                    ->default(null),
                TextInput::make('تاريخ إنتهاء أول ترخيص')
                    ->default(null),
                TextInput::make('رقم البطاقة الإلكترونية')
                    ->numeric()
                    ->default(null),
                TextInput::make('تاريخ إصدار البطاقة')
                    ->default(null),
                TextInput::make('تاريخ إنتهاء أول بطاقة')
                    ->default(null),
            ]);
    }
}
