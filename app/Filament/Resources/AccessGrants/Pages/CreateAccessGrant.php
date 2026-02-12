<?php

namespace App\Filament\Resources\AccessGrants\Pages;

use App\Filament\Resources\AccessGrants\AccessGrantResource;
use App\Models\AccessGrant;
use App\Models\User;
use App\Notifications\AccessGrantedNotification;
use App\Notifications\AccessGrantInvitationNotification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class CreateAccessGrant extends CreateRecord
{
    protected static string $resource = AccessGrantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['email'] = strtolower(trim($data['email']));
        $data['granted_by'] = Auth::id();
        $data['token'] = AccessGrant::generateToken();
        $data['status'] = AccessGrant::STATUS_PENDING;

        return $data;
    }

    protected function afterCreate(): void
    {
        $grant = $this->record;
        $user = User::where('email', $grant->email)->first();

        if ($user) {
            $grant->activate($user);
            $user->notify(new AccessGrantedNotification($grant));
        } else {
            Notification::route('mail', $grant->email)
                ->notify(new AccessGrantInvitationNotification($grant));
        }
    }
}
