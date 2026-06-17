<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SponsoringResource\Pages;
use App\Models\CampEdition;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class SponsoringResource extends Resource
{
    protected static ?string $model = CampEdition::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Camp';

    protected static ?string $navigationLabel = 'Sponsoring';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Activation')
                    ->schema([
                        Toggle::make('show_sponsoring_page')
                            ->label('Activer la page publique de sponsoring')
                            ->helperText('Si activé, la page /sponsoring sera visible par le public')
                            ->default(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Contenu de la page')
                    ->schema([
                        TextInput::make('sponsoring_theme')
                            ->label('Thème du camp')
                            ->placeholder('ex: Porteurs de Vie')
                            ->nullable()
                            ->columnSpanFull(),

                        TextInput::make('sponsoring_verse')
                            ->label('Verset biblique')
                            ->placeholder('ex: Ézéchiel 37:14a — « Je mettrai en vous mon Esprit »')
                            ->nullable()
                            ->columnSpanFull(),

                        Textarea::make('sponsoring_intro')
                            ->label('Introduction / Message aux sponsors')
                            ->rows(5)
                            ->nullable()
                            ->columnSpanFull(),
                    ]),

                Section::make('Progression')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('budget_total')
                                ->label('Budget total (FCFA)')
                                ->numeric()
                                ->default(6000000),

                            TextInput::make('budget_collected')
                                ->label('Montant collecté (FCFA)')
                                ->numeric()
                                ->default(0)
                                ->helperText('Mettre à jour manuellement à chaque don reçu'),

                            TextInput::make('participants_target')
                                ->label('Nombre de participants cibles')
                                ->numeric()
                                ->default(150),

                            TextInput::make('participants_sponsored')
                                ->label('Participants déjà sponsorisés')
                                ->numeric()
                                ->default(0),
                        ]),
                    ]),

                Section::make('Montants des bourses (FCFA)')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('bourse_pleine_amount')
                                ->label('Bourse pleine')
                                ->numeric()->default(40000),
                            TextInput::make('bourse_adulte_amount')
                                ->label('Adulte (sponsorisé)')
                                ->numeric()->default(30000),
                            TextInput::make('bourse_etudiant_amount')
                                ->label('Étudiant (sponsorisé)')
                                ->numeric()->default(20000),
                            TextInput::make('bourse_lycee_amount')
                                ->label('Lycée / Collège (sponsorisé)')
                                ->numeric()->default(15000),
                            TextInput::make('bourse_enfant_amount')
                                ->label('Enfant (sponsorisé)')
                                ->numeric()->default(10000),
                        ]),
                    ]),

                Section::make('Moyens de paiement')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('payment_flooz')
                                ->label('Flooz (numéro)')
                                ->placeholder('+228 99 XX XX XX')
                                ->nullable(),
                            TextInput::make('payment_mixx')
                                ->label('Mixx by YAS (numéro)')
                                ->placeholder('+228 93 XX XX XX')
                                ->nullable(),
                            TextInput::make('payment_account_name')
                                ->label('Intitulé du compte bancaire')
                                ->nullable(),
                            TextInput::make('payment_account_number')
                                ->label('Numéro de compte ECOBANK')
                                ->nullable(),
                            TextInput::make('payment_iban')
                                ->label('Code IBAN')
                                ->nullable(),
                            TextInput::make('payment_paypal')
                                ->label('Lien PayPal')
                                ->url()
                                ->nullable()
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Contact')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('sponsoring_contact_phone')
                                ->label('Téléphone de contact')
                                ->nullable(),
                            TextInput::make('sponsoring_contact_email')
                                ->label('Email de contact')
                                ->email()
                                ->nullable(),
                        ]),
                    ]),

                Section::make('Apports en nature')
                    ->schema([
                        Repeater::make('nature_contributions')
                            ->label('')
                            ->schema([
                                TextInput::make('designation')
                                    ->label('Désignation')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->addActionLabel('Ajouter un apport en nature')
                            ->defaultItems(0)
                            ->nullable()
                            ->columnSpanFull(),
                    ]),

                Section::make('Répartition des participants')
                    ->schema([
                        Grid::make(4)->schema([
                            TextInput::make('participants_adultes')
                                ->label('Adultes')->numeric()->default(0),
                            TextInput::make('participants_etudiants')
                                ->label('Étudiants')->numeric()->default(0),
                            TextInput::make('participants_lycee')
                                ->label('Lycée/Collège')->numeric()->default(0),
                            TextInput::make('participants_enfants')
                                ->label('Enfants')->numeric()->default(0),
                        ]),

                        Repeater::make('participants_geo')
                            ->label('Répartition géographique')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('ville')
                                        ->label('Ville/Zone')->required(),
                                    TextInput::make('nombre')
                                        ->label('Nombre')->numeric()->required(),
                                ]),
                            ])
                            ->addActionLabel('Ajouter une ville')
                            ->defaultItems(0)
                            ->nullable()
                            ->columnSpanFull(),
                    ]),

                Section::make('Budget prévisionnel — Dépenses')
                    ->schema([
                        Repeater::make('budget_expenses')
                            ->label('')
                            ->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('designation')
                                        ->label('Désignation')->required(),
                                    TextInput::make('prix_unitaire')
                                        ->label('Prix unitaire (FCFA)')->numeric()->default(0),
                                    TextInput::make('quantite')
                                        ->label('Quantité')->numeric()->default(1),
                                ]),
                            ])
                            ->addActionLabel('Ajouter une ligne de dépense')
                            ->defaultItems(0)
                            ->nullable()
                            ->columnSpanFull(),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Édition')
                    ->sortable(),
                TextColumn::make('year')
                    ->label('Année')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                IconColumn::make('show_sponsoring_page')
                    ->label('Sponsoring public')
                    ->boolean(),
                TextColumn::make('budget_collected')
                    ->label('Collecté (FCFA)')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', ' ')),
                TextColumn::make('budget_total')
                    ->label('Budget total (FCFA)')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', ' ')),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('year', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSponsorings::route('/'),
            'edit'  => Pages\EditSponsoring::route('/{record}/edit'),
        ];
    }
}
