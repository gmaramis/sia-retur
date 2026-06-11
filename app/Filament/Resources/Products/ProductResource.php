<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static string|\UnitEnum|null $navigationGroup = 'Data Master';

    protected static ?string $navigationLabel = 'Barang';

    protected static ?string $modelLabel = 'Barang';

    protected static ?string $pluralModelLabel = 'Barang';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Barang')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('name')
                    ->label('Nama Barang')
                    ->required(),
                Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'Beras' => 'Beras',
                        'Gula' => 'Gula',
                        'Minyak' => 'Minyak',
                        'Tepung' => 'Tepung',
                        'Telur' => 'Telur',
                        'Mie' => 'Mie',
                        'Bumbu' => 'Bumbu',
                        'Lainnya' => 'Lainnya',
                    ]),
                Select::make('unit')
                    ->label('Satuan')
                    ->options([
                        'pcs' => 'Pcs',
                        'kg' => 'Kg',
                        'liter' => 'Liter',
                        'dus' => 'Dus',
                        'karung' => 'Karung',
                        'pack' => 'Pack',
                    ])
                    ->default('pcs'),
                TextInput::make('minimum_stock')
                    ->label('Stok Minimal')
                    ->numeric()
                    ->default(0),
                TextInput::make('shelf_life_days')
                    ->label('Umur Simpan (Hari)')
                    ->numeric(),
                TextInput::make('default_purchase_price')
                    ->label('Harga Beli Default')
                    ->numeric()
                    ->prefix('Rp'),
                TextInput::make('stock_ready')
                    ->label('Stok Ready')
                    ->numeric()
                    ->default(0)
                    ->disabled(),
                TextInput::make('stock_hold')
                    ->label('Stok Hold')
                    ->numeric()
                    ->default(0)
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),
                TextColumn::make('unit')
                    ->label('Satuan'),
                TextColumn::make('minimum_stock')
                    ->label('Stok Minimal')
                    ->sortable(),
                TextColumn::make('default_purchase_price')
                    ->label('Harga Beli')
                    ->money('IDR'),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('stock_ready')
                    ->label('stock_ready'),
                TextColumn::make('stock_hold')
                    ->label('stock_hold')
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
