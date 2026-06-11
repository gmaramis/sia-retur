<?php

namespace App\Filament\Resources\PurchaseReturns\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchaseReturnsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('return_number')
                    ->label('Nomor Retur')
                    ->searchable(),
                TextColumn::make('supplier.name')
                    ->searchable(),
                TextColumn::make('receivingIssue.product.name')
                    ->label('Nama Barang')
                    ->searchable(),
                TextColumn::make('receivingIssue.issue_type')
                    ->label('Jenis Kerusakan')
                    ->formatStateUsing(fn ($state, $record) =>
                        $state . ' (Qty ' . $record->receivingIssue->quantity  . ')' 
                    ),                    
                TextColumn::make('return_date')
                    ->label('Tanggal Return')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
}
