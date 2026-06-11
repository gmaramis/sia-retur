<?php

namespace App\Filament\Resources\GoodsReceipts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class GoodsReceiptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('receipt_number')
                    ->required(),
                Select::make('purchase_order_id')
                    ->relationship('purchaseOrder', 'id')
                    ->required(),
                Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->required(),
                DatePicker::make('receipt_date')
                    ->required(),
                TextInput::make('delivery_note_number'),
                TextInput::make('invoice_number'),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
