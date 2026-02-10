<?php

namespace App\Filament\Resources\TradePairs\Pages;

use App\Filament\Resources\TradePairs\TradePairResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTradePairs extends ListRecords
{
    protected static string $resource = TradePairResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
