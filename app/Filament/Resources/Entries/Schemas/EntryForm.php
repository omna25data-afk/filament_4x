<?php

namespace App\Filament\Resources\Entries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('register_id')
                    ->required()
                    ->numeric(),
                TextInput::make('contract_type_id')
                    ->required()
                    ->numeric(),
                TextInput::make('writer_type_id')
                    ->required()
                    ->numeric(),
                TextInput::make('writer_user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('writer_notary_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('writer_other_id')
                    ->numeric()
                    ->default(null),
                DatePicker::make('document_hijri_date')
                    ->required(),
                DatePicker::make('document_gregorian_date'),
                TextInput::make('document_paper_number')
                    ->default(null),
                Select::make('entry_status')
                    ->options([
            'draft' => 'Draft',
            'pending_certification' => 'Pending certification',
            'certified' => 'Certified',
            'delivered_to_concerned' => 'Delivered to concerned',
            'rejected' => 'Rejected',
        ])
                    ->default('draft')
                    ->required(),
                TextInput::make('certifier_user_id')
                    ->numeric()
                    ->default(null),
                DatePicker::make('certification_hijri_date'),
                DatePicker::make('certification_gregorian_date'),
                TextInput::make('court_register_entry_number')
                    ->default(null),
                TextInput::make('court_register_page_number')
                    ->numeric()
                    ->default(null),
                TextInput::make('court_register_number')
                    ->numeric()
                    ->default(null),
                TextInput::make('court_box_number')
                    ->default(null),
                DatePicker::make('delivery_hijri_date'),
                DatePicker::make('delivery_gregorian_date'),
                FileUpload::make('delivery_receipt_image_path')
                    ->image(),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
