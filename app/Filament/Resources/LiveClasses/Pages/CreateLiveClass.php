<?php

namespace App\Filament\Resources\LiveClasses\Pages;

use App\Filament\Resources\LiveClasses\LiveClassResource;
use App\Notifications\LiveClassScheduledNotification;
use Filament\Resources\Pages\CreateRecord;

class CreateLiveClass extends CreateRecord
{
    protected static string $resource = LiveClassResource::class;

    protected function afterCreate(): void
    {
        $liveClass = $this->record;

        // Auto-register enrolled students
        $liveClass->registerStudents();

        // Send scheduled notification to all registered attendees
        $liveClass->load('attendances.user');

        foreach ($liveClass->attendances as $attendance) {
            $attendance->user->notify(
                new LiveClassScheduledNotification($liveClass, $attendance->access_token)
            );
        }
    }
}
