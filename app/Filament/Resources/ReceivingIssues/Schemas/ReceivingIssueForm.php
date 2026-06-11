<?php

namespace App\Filament\Resources\ReceivingIssues\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReceivingIssueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('goods_receipt_id')
                    ->relationship('goodsReceipt', 'id')
                    ->required(),
                Select::make('goods_receipt_item_id')
                    ->relationship('goodsReceiptItem', 'id')
                    ->required(),
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                TextInput::make('issue_type')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('photo_path'),
                TextInput::make('status')
                    ->required()
                    ->default('hold'),
            ]);
    }
}
