<?php

namespace App\Filament\Resources\JournalApiKeys\Pages;

use App\Filament\Resources\JournalApiKeys\JournalApiKeyResource;
use App\Models\JournalApiKey;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateJournalApiKey extends CreateRecord
{
    protected static string $resource = JournalApiKeyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $plainKey = JournalApiKey::generateKey();

        $data['key_hash'] = hash('sha256', $plainKey);
        $data['key_prefix'] = substr($plainKey, 0, 8);

        // Store temporarily so we can show it after creation
        session()->flash('journal_api_key_plain', $plainKey);

        return $data;
    }

    protected function afterCreate(): void
    {
        $plainKey = session('journal_api_key_plain');

        if ($plainKey) {
            Notification::make()
                ->title('API Key generada')
                ->body("Copia esta clave ahora, no se mostrara de nuevo:\n\n{$plainKey}")
                ->persistent()
                ->success()
                ->send();
        }
    }
}
