<?php

namespace App\Filament\Resources\PurchaseReturns\RelationManagers;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use App\Models\Product;
use App\Models\ReceivingIssue;
class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $relatedResource = ProductResource::class;

    public function form(Schema $schema): Schema
{
    return $schema->components([
        Forms\Components\Select::make('product_id')
            ->options(function ($livewire) {
                $issue = ReceivingIssue::find(
                $livewire->ownerRecord->receiving_issue_id
            );

    return Product::where('id', $issue?->product_id)
        ->pluck('name', 'id');
})
            ->searchable()
            ->required(),

        Forms\Components\TextInput::make('quantity')
            ->numeric()
            ->live()
            ->required()
            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                $set(
                    'subtotal',
                    ((float) $state) * ((float) $get('unit_price'))
                );
            }),

        Forms\Components\TextInput::make('unit_price')
            ->numeric()
            ->live()
            ->required()
            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                $set(
                    'subtotal',
                    ((float) $get('quantity')) * ((float) $state)
                );
            }),

        Forms\Components\TextInput::make('subtotal')
            ->numeric()
            ->readOnly(),
    ]);
}

    public function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('product.name')
                ->label('Barang'),

            TextColumn::make('quantity')
                ->label('Qty Retur'),

            TextColumn::make('unit_price')
                ->money('IDR'),

            TextColumn::make('subtotal')
                ->money('IDR'),
        ])
        ->headerActions([
            CreateAction::make(),
        ]);
}
}
