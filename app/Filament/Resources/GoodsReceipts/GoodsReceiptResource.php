<?php

namespace App\Filament\Resources\GoodsReceipts;

use App\Filament\Resources\GoodsReceipts\Pages\CreateGoodsReceipt;
use App\Filament\Resources\GoodsReceipts\Pages\EditGoodsReceipt;
use App\Filament\Resources\GoodsReceipts\Pages\ListGoodsReceipts;
use App\Models\GoodsReceipt;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GoodsReceiptResource extends Resource
{
    protected static ?string $model = GoodsReceipt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static string|\UnitEnum|null $navigationGroup = 'Penerimaan';

    protected static ?string $navigationLabel = 'Penerimaan Barang';

    protected static ?string $modelLabel = 'Penerimaan Barang';

    protected static ?string $pluralModelLabel = 'Penerimaan Barang';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'receipt_number';

    public static function getStatusOptions(): array
    {
        return [
            'draft' => 'Draf',
            'checked' => 'Diperiksa',
            'has_issue' => 'Ada Masalah',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('receipt_number')
                    ->label('Nomor Penerimaan')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('purchase_order_id')
                    ->label('Pesanan Pembelian')
                    ->relationship('purchaseOrder', 'po_number')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('supplier_id')
                    ->label('Pemasok')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('receipt_date')
                    ->label('Tanggal Penerimaan')
                    ->required()
                    ->default(now()),
                TextInput::make('delivery_note_number')
                    ->label('Nomor Surat Jalan'),
                TextInput::make('invoice_number')
                    ->label('Nomor Faktur'),
                Select::make('status')
                    ->label('Status')
                    ->options(static::getStatusOptions())
                    ->default('draft'),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('receipt_number')
                    ->label('Nomor Penerimaan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('purchaseOrder.po_number')
                    ->label('Nomor PO')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('supplier.name')
                    ->label('Pemasok')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('receipt_date')
                    ->label('Tanggal Penerimaan')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::getStatusOptions()[$state] ?? $state),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGoodsReceipts::route('/'),
            'create' => CreateGoodsReceipt::route('/create'),
            'edit' => EditGoodsReceipt::route('/{record}/edit'),
        ];
    }
}
