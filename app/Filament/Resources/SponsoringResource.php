<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SponsoringResource\Pages;
use App\Models\CampEdition;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
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

                        TextInput::make('sponsoring_salutation')
                            ->label('Salutation')
                            ->placeholder('Ex: Chers amis,')
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

                Section::make('Bourses (types de dons)')
                    ->description('Bourse pleine et bourse partielle affichées côte à côte')
                    ->schema([

                        TextInput::make('title_bourses')
                            ->label('Titre de cette section (personnalisable)')
                            ->placeholder('Types de bourses')
                            ->helperText('Laissez vide pour utiliser : "Types de bourses"')
                            ->nullable()
                            ->columnSpanFull(),

                        Grid::make(2)->schema([
                            TextInput::make('bourse_pleine_label')
                                ->label('Label bourse pleine')
                                ->default('Bourse Pleine'),
                            TextInput::make('bourse_pleine_desc')
                                ->label('Description bourse pleine')
                                ->default('Couvrez l\'intégralité des frais d\'un jeune'),
                            TextInput::make('bourse_pleine_amount')
                                ->label('Montant bourse pleine (FCFA)')
                                ->numeric()->default(40000),
                            TextInput::make('bourse_partielle_label')
                                ->label('Label bourse partielle')
                                ->default('Bourse Partielle'),
                            TextInput::make('bourse_partielle_desc')
                                ->label('Description bourse partielle')
                                ->default('Contribuez selon votre cœur'),
                        ]),
                    ]),

                Section::make('Frais de participation par catégorie')
                    ->description('Coûts réels affichés séparément des bourses')
                    ->schema([

                        TextInput::make('title_frais')
                            ->label('Titre de cette section (personnalisable)')
                            ->placeholder('Frais de participation par catégorie')
                            ->helperText('Laissez vide pour utiliser : "Frais de participation par catégorie"')
                            ->nullable()
                            ->columnSpanFull(),

                        Grid::make(2)->schema([
                            TextInput::make('categorie_adulte_label')
                                ->label('Label catégorie 1')
                                ->default('Adulte'),
                            TextInput::make('bourse_adulte_amount')
                                ->label('Montant catégorie 1 (FCFA)')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->required(),
                            TextInput::make('categorie_etudiant_label')
                                ->label('Label catégorie 2')
                                ->default('Étudiant'),
                            TextInput::make('bourse_etudiant_amount')
                                ->label('Montant catégorie 2 (FCFA)')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->required(),
                            TextInput::make('categorie_lycee_label')
                                ->label('Label catégorie 3')
                                ->default('Lycée / Collège'),
                            TextInput::make('bourse_lycee_amount')
                                ->label('Montant catégorie 3 (FCFA)')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->required(),
                            TextInput::make('categorie_enfant_label')
                                ->label('Label catégorie 4')
                                ->default('Enfant'),
                            TextInput::make('bourse_enfant_amount')
                                ->label('Montant catégorie 4 (FCFA)')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->required(),
                        ]),
                    ]),

                Section::make('Moyens de paiement')
                    ->schema([

                        TextInput::make('title_paiement')
                            ->label('Titre de cette section (personnalisable)')
                            ->placeholder('Comment contribuer ?')
                            ->helperText('Laissez vide pour utiliser : "Comment contribuer ?"')
                            ->nullable()
                            ->columnSpanFull(),

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

                Section::make('Liens externes (budget, documents, etc.)')
                    ->description('Ajoutez un ou plusieurs liens vers des documents ou pages externes (budget prévisionnel, programme, etc.)')
                    ->schema([
                        Repeater::make('external_links')
                            ->label('')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('label')
                                        ->label('Titre du bouton')
                                        ->placeholder('ex: Consulter le budget prévisionnel')
                                        ->required(),
                                    TextInput::make('url')
                                        ->label('URL du lien')
                                        ->url()
                                        ->placeholder('https://...')
                                        ->required(),
                                ]),
                            ])
                            ->addActionLabel('Ajouter un lien')
                            ->defaultItems(0)
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

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

                        TextInput::make('title_nature')
                            ->label('Titre de cette section (personnalisable)')
                            ->placeholder('Apports en nature')
                            ->helperText('Laissez vide pour utiliser : "Apports en nature"')
                            ->nullable()
                            ->columnSpanFull(),

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

                Section::make('Budget prévisionnel — Dépenses')
                    ->schema([
                        Repeater::make('budget_expenses')
                            ->label('')
                            ->schema([
                                Grid::make(4)->schema([
                                    TextInput::make('designation')
                                        ->label('Désignation')->required(),
                                    TextInput::make('prix_unitaire')
                                        ->label('Prix unitaire (FCFA)')->numeric()->default(0),
                                    TextInput::make('quantite')
                                        ->label('Quantité')->numeric()->default(1),
                                    Placeholder::make('montant')
                                        ->label('Montant (FCFA)')
                                        ->content(function ($get) {
                                            $prix = $get('prix_unitaire') ?? 0;
                                            $qte = $get('quantite') ?? 1;
                                            return number_format($prix * $qte, 0, ',', ' ');
                                        }),
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
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->label('Supprimer la sélection')
                        ->modalHeading('⚠️ Supprimer les éditions sélectionnées')
                        ->modalDescription('ATTENTION : La suppression d\'une édition supprimera également toutes les inscriptions associées. Cette action est irréversible.')
                        ->modalSubmitActionLabel('Oui, supprimer définitivement')
                        ->color('danger'),
                ]),
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
