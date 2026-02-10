<?php

namespace App\Filament\Resources\TradePairs\Pages;

use App\Filament\Resources\TradePairs\TradePairResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTradePair extends EditRecord
{
    protected static string $resource = TradePairResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
