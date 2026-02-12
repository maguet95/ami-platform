<?php

namespace App\Filament\Resources\AccessGrants\Tables;

use App\Models\AccessGrant;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AccessGrantInvitationNotification;

class AccessGrantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->placeholder('Sin registrar')
                    ->searchable(),
                TextColumn::make('duration_type')
                    ->label('Duración')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => AccessGrant::durationOptions()[$state] ?? $state)
                    ->color('info'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => AccessGrant::statusOptions()[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'active' => 'success',
                        'expired' => 'gray',
                        'revoked' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('expires_at')
                    ->label('Expira')
                    ->dateTime('d/m/Y')
                    ->placeholder('Nunca')
                    ->sortable(),
                TextColumn::make('grantedByUser.name')
                    ->label('Otorgado por')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(AccessGrant::statusOptions()),
                SelectFilter::make('duration_type')
                    ->label('Duración')
                    ->options(AccessGrant::durationOptions()),
            ])
            ->recordActions([
                Action::make('revoke')
                    ->label('Revocar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Revocar acceso')
                    ->modalDescription('El usuario perderá el acceso premium inmediatamente.')
                    ->visible(fn (AccessGrant $record) => in_array($record->status, ['active', 'pending']))
                    ->action(function (AccessGrant $record) {
                        $record->revoke();
                        FilamentNotification::make()
                            ->title('Acceso revocado')
                            ->success()
                            ->send();
                    }),
                Action::make('resend')
                    ->label('Reenviar')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->visible(fn (AccessGrant $record) => $record->status === 'pending')
                    ->action(function (AccessGrant $record) {
                        Notification::route('mail', $record->email)
                            ->notify(new AccessGrantInvitationNotification($record));
                        FilamentNotification::make()
                            ->title('Invitación reenviada')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
