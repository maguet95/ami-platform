<?php

namespace App\Filament\Instructor\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información Personal')
                    ->description('Tu nombre, correo y contraseña de acceso.')
                    ->aside()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getCurrentPasswordFormComponent(),
                    ]),

                Section::make('Perfil Público')
                    ->description('Esta información será visible en tu perfil público y en la página del equipo.')
                    ->aside()
                    ->schema([
                        TextInput::make('username')
                            ->label('Username')
                            ->prefix('@')
                            ->maxLength(30)
                            ->unique(ignoreRecord: true)
                            ->alphaDash(),
                        TextInput::make('headline')
                            ->label('Titular profesional')
                            ->placeholder('Ej: Trader Profesional | Especialista en Price Action')
                            ->maxLength(100),
                        Textarea::make('bio')
                            ->label('Biografía')
                            ->placeholder('Cuéntale a la comunidad sobre tu experiencia...')
                            ->rows(4)
                            ->maxLength(500),
                        FileUpload::make('avatar')
                            ->label('Foto de perfil')
                            ->image()
                            ->imageEditor()
                            ->circleCropper()
                            ->directory('avatars')
                            ->maxSize(2048),
                        TextInput::make('location')
                            ->label('Ubicación')
                            ->placeholder('Ej: Bogotá, Colombia')
                            ->maxLength(100),
                    ]),

                Section::make('Redes Sociales')
                    ->description('Tus perfiles en redes sociales. Se mostrarán en tu perfil público.')
                    ->aside()
                    ->schema([
                        TextInput::make('twitter_handle')
                            ->label('X (Twitter)')
                            ->prefix('@')
                            ->placeholder('tu_usuario')
                            ->maxLength(50),
                        TextInput::make('instagram_handle')
                            ->label('Instagram')
                            ->prefix('@')
                            ->placeholder('tu_usuario')
                            ->maxLength(50),
                        TextInput::make('youtube_handle')
                            ->label('YouTube')
                            ->prefix('@')
                            ->placeholder('tu_canal')
                            ->maxLength(100),
                        TextInput::make('linkedin_url')
                            ->label('LinkedIn')
                            ->placeholder('https://linkedin.com/in/tu-perfil')
                            ->url()
                            ->maxLength(255),
                    ]),

                Section::make('Trading')
                    ->description('Información sobre tu carrera como trader.')
                    ->aside()
                    ->schema([
                        DatePicker::make('trading_since')
                            ->label('Trader desde')
                            ->native(false)
                            ->maxDate(now()),
                    ]),
            ]);
    }
}
