<?php

namespace App\Filament\Instructor\Resources\LiveClasses\Pages;

use App\Filament\Instructor\Resources\LiveClasses\LiveClassResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLiveClass extends EditRecord
{
    protected static string $resource = LiveClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
