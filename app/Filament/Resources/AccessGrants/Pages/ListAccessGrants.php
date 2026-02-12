<?php

namespace App\Filament\Resources\AccessGrants\Pages;

use App\Filament\Resources\AccessGrants\AccessGrantResource;
use App\Models\AccessGrant;
use App\Models\User;
use App\Notifications\AccessGrantedNotification;
use App\Notifications\AccessGrantInvitationNotification;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ListAccessGrants extends ListRecords
{
    protected static string $resource = AccessGrantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Otorgar acceso'),

            Action::make('batch_grant')
                ->label('Acceso masivo')
                ->icon('heroicon-o-user-group')
                ->color('info')
                ->form([
                    Textarea::make('emails')
                        ->label('Emails (uno por línea, o separados por coma/punto y coma)')
                        ->required()
                        ->rows(6)
                        ->placeholder("usuario1@ejemplo.com\nusuario2@ejemplo.com\nusuario3@ejemplo.com"),
                    Select::make('duration_type')
                        ->label('Duración')
                        ->options(AccessGrant::durationOptions())
                        ->default(AccessGrant::DURATION_1_MONTH)
                        ->required(),
                    Textarea::make('notes')
                        ->label('Notas')
                        ->rows(2)
                        ->maxLength(500),
                ])
                ->action(function (array $data) {
                    $rawEmails = $data['emails'];
                    $emails = collect(preg_split('/[\n,;]+/', $rawEmails))
                        ->map(fn ($e) => strtolower(trim($e)))
                        ->filter(fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
                        ->unique()
                        ->values();

                    if ($emails->isEmpty()) {
                        FilamentNotification::make()
                            ->title('No se encontraron emails válidos')
                            ->danger()
                            ->send();
                        return;
                    }

                    $created = 0;
                    $skipped = 0;

                    foreach ($emails as $email) {
                        // Skip if active/pending grant already exists for this email
                        $exists = AccessGrant::forEmail($email)
                            ->whereIn('status', [AccessGrant::STATUS_ACTIVE, AccessGrant::STATUS_PENDING])
                            ->exists();

                        if ($exists) {
                            $skipped++;
                            continue;
                        }

                        $grant = AccessGrant::create([
                            'email' => $email,
                            'granted_by' => Auth::id(),
                            'duration_type' => $data['duration_type'],
                            'status' => AccessGrant::STATUS_PENDING,
                            'token' => AccessGrant::generateToken(),
                            'notes' => $data['notes'] ?? null,
                        ]);

                        $user = User::where('email', $email)->first();

                        if ($user) {
                            $grant->activate($user);
                            $user->notify(new AccessGrantedNotification($grant));
                        } else {
                            Notification::route('mail', $email)
                                ->notify(new AccessGrantInvitationNotification($grant));
                        }

                        $created++;
                    }

                    $message = "{$created} acceso(s) otorgado(s)";
                    if ($skipped > 0) {
                        $message .= ", {$skipped} omitido(s) (ya tenían acceso)";
                    }

                    FilamentNotification::make()
                        ->title($message)
                        ->success()
                        ->send();
                }),
        ];
    }
}
