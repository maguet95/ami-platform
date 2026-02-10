<?php

namespace App\Filament\Resources\JournalApiKeys\Pages;

use App\Filament\Resources\JournalApiKeys\JournalApiKeyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJournalApiKeys extends ListRecords
{
    protected static string $resource = JournalApiKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
