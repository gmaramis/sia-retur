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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
                    ->options([
                        'pcs' => 'pcs',
                        'kg' => 'kg',
                        'liter' => 'liter',
                        'dus' => 'dus',
                        'karung' => 'karung',
                        'pack' => 'pack',
                    ])
                    ->default('pcs'),
                TextInput::make('minimum_stock')
                    ->label('Stok Minimal')
                    ->numeric()
                    ->default(0),
                TextInput::make('shelf_life_days')
                    ->label('Umur Simpan Hari')
                    ->numeric(),
                TextInput::make('default_purchase_price')
                    ->label('Harga Beli Default')
                    ->numeric()
                    ->prefix('Rp'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->badge(),
                TextColumn::make('unit'),
                TextColumn::make('minimum_stock')
                    ->sortable(),
                TextColumn::make('default_purchase_price')
                    ->money('IDR'),
                TextColumn::make('created_at')
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
