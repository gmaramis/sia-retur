<?php

namespace App\Filament\Resources\ReceivingIssues\Pages;

use App\Filament\Resources\ReceivingIssues\ReceivingIssueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReceivingIssue extends EditRecord
{
    protected static string $resource = ReceivingIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
