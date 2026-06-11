<?php

namespace App\Filament\Resources\GoodsReceipts\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Detail Barang Penerimaan';

    protected static ?string $modelLabel = 'Barang Penerimaan';

    protected static ?string $pluralModelLabel = 'Detail Barang Penerimaan';

    public static function getConditionStatusOptions(): array
    {
        return [
            'good' => 'Baik',
            'hold' => 'Ditahan',
            'partial_issue' => 'Sebagian Bermasalah',
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Barang')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('ordered_quantity')
                    ->label('Qty PO')
                    ->numeric()
                    ->default(0)
                    ->required(),
                TextInput::make('received_quantity')
                    ->label('Qty Diterima')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set): void {
                        $receivedQuantity = (float) ($get('received_quantity') ?? 0);
                        $unitPrice = (float) ($get('unit_price') ?? 0);

                        $set('subtotal', number_format($receivedQuantity * $unitPrice, 2, '.', ''));
                    }),
                TextInput::make('good_quantity')
                    ->label('Qty Baik')
                    ->numeric()
                    ->default(0)
                    ->required(),
                TextInput::make('hold_quantity')
                    ->label('Qty Ditahan')
                    ->numeric()
                    ->default(0)
                    ->required(),
                TextInput::make('unit_price')
                    ->label('Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set): void {
                        $receivedQuantity = (float) ($get('received_quantity') ?? 0);
                        $unitPrice = (float) ($get('unit_price') ?? 0);

                        $set('subtotal', number_format($receivedQuantity * $unitPrice, 2, '.', ''));
                    }),
                TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated(false)
                    ->default('0.00'),
                DatePicker::make('expiry_date')
                    ->label('Tanggal Kedaluwarsa'),
                Select::make('condition_status')
                    ->label('Kondisi')
                    ->options(static::getConditionStatusOptions())
                    ->default('good')
                    ->required(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                TextColumn::make('product.name')
                    ->label('Barang')
                    ->searchable(),
                TextColumn::make('ordered_quantity')
                    ->label('Qty PO')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('received_quantity')
                    ->label('Qty Diterima')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('good_quantity')
                    ->label('Qty Baik')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('hold_quantity')
                    ->label('Qty Ditahan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('condition_status')
                    ->label('Kondisi')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::getConditionStatusOptions()[$state] ?? $state),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Barang'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
