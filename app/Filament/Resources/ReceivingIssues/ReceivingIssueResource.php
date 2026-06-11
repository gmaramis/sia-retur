<?php

namespace App\Filament\Resources\ReceivingIssues;

use App\Filament\Resources\ReceivingIssues\Pages\CreateReceivingIssue;
use App\Filament\Resources\ReceivingIssues\Pages\EditReceivingIssue;
use App\Filament\Resources\ReceivingIssues\Pages\ListReceivingIssues;
use App\Models\ReceivingIssue;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReceivingIssueResource extends Resource
{
    protected static ?string $model = ReceivingIssue::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|\UnitEnum|null $navigationGroup = 'Penerimaan';

    protected static ?string $navigationLabel = 'Masalah Penerimaan';

    protected static ?string $modelLabel = 'Masalah Penerimaan';

    protected static ?string $pluralModelLabel = 'Masalah Penerimaan';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'issue_type';

    public static function getIssueTypeOptions(): array
    {
        return [
            'rusak' => 'Rusak',
            'bocor' => 'Bocor',
            'penyok' => 'Penyok',
            'kedaluwarsa_dekat' => 'Kedaluwarsa Dekat',
            'salah_item' => 'Salah Item',
            'kurang_jumlah' => 'Kurang Jumlah',
        ];
    }

    public static function getStatusOptions(): array
    {
        return [
            'hold' => 'Ditahan',
            'prepared_for_return' => 'Siap Retur',
            'returned' => 'Sudah Diretur',
            'resolved' => 'Selesai',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('goods_receipt_id')
                    ->label('Penerimaan Barang')
                    ->relationship('goodsReceipt', 'receipt_number')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('goods_receipt_item_id')
                    ->label('Barang Bermasalah')
                    ->relationship('goodsReceiptItem', 'id')
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => $record->product->name
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('product_id')
                    ->label('Barang')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('issue_type')
                    ->label('Jenis Masalah')
                    ->options(static::getIssueTypeOptions())
                    ->required(),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->required(),
                Textarea::make('description')
                    ->label('Keterangan')
                    ->columnSpanFull(),
                FileUpload::make('photo_path')
                    ->label('Foto Bukti')
                    ->image()
                    ->directory('receiving-issues'),
                Select::make('status')
                    ->label('Status')
                    ->options(static::getStatusOptions())
                    ->default('hold')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('goodsReceipt.receipt_number')
                    ->label('Nomor Penerimaan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('issue_type')
                    ->label('Jenis Masalah')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => static::getIssueTypeOptions()[$state] ?? $state),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => static::getStatusOptions()[$state] ?? $state),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReceivingIssues::route('/'),
            'create' => CreateReceivingIssue::route('/create'),
            'edit' => EditReceivingIssue::route('/{record}/edit'),
        ];
    }
}
