<?php

namespace App\Filament\Resources\ReceivingIssues\Pages;

use App\Filament\Resources\ReceivingIssues\ReceivingIssueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReceivingIssues extends ListRecords
{
    protected static string $resource = ReceivingIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
