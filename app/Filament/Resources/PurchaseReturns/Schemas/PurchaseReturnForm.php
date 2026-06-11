<?php

namespace App\Filament\Resources\PurchaseReturns\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use App\Models\ReceivingIssue;
use Filament\Schemas\Schema;

class PurchaseReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('return_number')
                    ->required(),
                Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('receiving_issue_id')
                    ->label('Receiving Issue')
                    ->options(
                        ReceivingIssue::with('product')
                            ->get()
                            ->mapWithKeys(fn ($issue) => [
                                $issue->id => ($issue->product?->name ?? 'Produk')
                                . ' - '
                                . $issue->issue_type
                                . ' - Qty '
                                . $issue->quantity,
                        ])
    )
    ->searchable()
    ->required(),
                DatePicker::make('return_date')
                    ->required(),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Dikirim ke Supplier',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->default('draft')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
