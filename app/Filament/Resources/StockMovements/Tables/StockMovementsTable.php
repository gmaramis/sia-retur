<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('movement_date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Barang')
                    ->searchable(),
                TextColumn::make('movement_type')
                    ->label('Aktivitas')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'IN_READY' => 'Penerimaan Barang Baik',
                        'IN_HOLD' => 'Penerimaan Barang Bermasalah',
                        'OUT_HOLD' => 'Retur ke Supplier',
                        default => $state,
                    })
                    ->badge(),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(50),
            ])
            ->filters([
                //
            ]);
    }
}
