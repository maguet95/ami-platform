<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    // Left Column (2/3)
                    Section::make('Información del Plan')
                        ->columnSpan(2)
                        ->schema([
                            TextInput::make('name')
                                ->label('Nombre')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                            TextInput::make('slug')
                                ->label('URL amigable')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                            Textarea::make('description')
                                ->label('Descripción')
                                ->rows(3)
                                ->maxLength(500),
                            Repeater::make('features')
                                ->label('Características')
                                ->simple(
                                    TextInput::make('feature')
                                        ->label('Característica')
                                        ->required(),
                                )
                                ->defaultItems(1)
                                ->reorderable()
                                ->collapsible()
                                ->helperText('Lista de beneficios que se muestran en la tarjeta del plan.'),
                        ]),

                    // Right Column (1/3)
                    Grid::make(1)->columnSpan(1)->schema([
                        Section::make('Stripe')
                            ->schema([
                                TextInput::make('stripe_product_id')
                                    ->label('Product ID')
                                    ->helperText('ID del producto en Stripe (prod_...)'),
                                TextInput::make('stripe_price_id')
                                    ->label('Price ID')
                                    ->helperText('ID del precio en Stripe (price_...)'),
                            ]),

                        Section::make('Precio')
                            ->schema([
                                TextInput::make('price')
                                    ->label('Precio')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$'),
                                Select::make('currency')
                                    ->label('Moneda')
                                    ->options([
                                        'USD' => 'USD',
                                    ])
                                    ->default('USD')
                                    ->required(),
                                Select::make('interval')
                                    ->label('Intervalo')
                                    ->options([
                                        'monthly' => 'Mensual',
                                        'yearly' => 'Anual',
                                    ])
                                    ->required(),
                            ]),

                        Section::make('Configuración')
                            ->schema([
                                TextInput::make('sort_order')
                                    ->label('Orden')
                                    ->numeric()
                                    ->default(0),
                                Toggle::make('is_active')
                                    ->label('Activo')
                                    ->default(true)
                                    ->helperText('Los planes inactivos no se muestran en la página de precios.'),
                                Toggle::make('is_featured')
                                    ->label('Destacado')
                                    ->helperText('El plan destacado se resalta visualmente.'),
                            ]),
                    ]),
                ]),
            ]);
    }
}
