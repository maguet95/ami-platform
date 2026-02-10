<?php

namespace App\Filament\Resources\JournalApiKeys\Pages;

use App\Filament\Resources\JournalApiKeys\JournalApiKeyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJournalApiKey extends EditRecord
{
    protected static string $resource = JournalApiKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
