<?php

namespace App\Filament\Resources\Enrollments\Pages;

use App\Filament\Resources\Enrollments\EnrollmentResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewEnrollment extends ViewRecord
{
    protected static string $resource = EnrollmentResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles de la Inscripción')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Estudiante'),
                        TextEntry::make('user.email')
                            ->label('Correo'),
                        TextEntry::make('course.title')
                            ->label('Curso'),
                        TextEntry::make('status')
                            ->label('Estado')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => match ($state) {
                                'active' => 'Activa',
                                'completed' => 'Completada',
                                'expired' => 'Expirada',
                                'cancelled' => 'Cancelada',
                                default => $state,
                            })
                            ->color(fn (string $state) => match ($state) {
                                'active' => 'success',
                                'completed' => 'info',
                                'expired' => 'warning',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('progress_percent')
                            ->label('Progreso')
                            ->suffix('%'),
                        TextEntry::make('enrolled_at')
                            ->label('Fecha de inscripción')
                            ->dateTime('d/m/Y H:i'),
                        TextEntry::make('completed_at')
                            ->label('Fecha de completado')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('—'),
                        TextEntry::make('expires_at')
                            ->label('Expira')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('Sin expiración'),
                    ])
                    ->columns(2),
            ]);
    }
}
