<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\CampEditionStatus;
use App\Filament\Resources\CampEditionResource\Pages;
use App\Filament\Resources\CampEditionResource\RelationManagers;
use App\Models\CampEdition;
use App\Services\CampEditionService;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CampEditionResource extends Resource
{
    protected static ?string $model = CampEdition::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Camp';

    protected static ?string $modelLabel = 'edition du camp';

    protected static ?string $pluralModelLabel = 'editions du camp';

    protected static ?string $navigationLabel = 'Editions du camp';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations generales')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->placeholder('Exemple : Camp 2026')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Forms\Set $set): void {
                                if ($state !== null) {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        Forms\Components\FileUpload::make('cover_image_path')
                            ->label('Image de couverture')
                            ->helperText('L\'image sera recadrée automatiquement au format bannière 4:1')
                            ->image()
                            ->disk('public')
                            ->directory('editions/covers')
                            ->imageEditor()
                            ->imageEditorAspectRatios(['4:1'])
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('1600')
                            ->imageResizeTargetHeight('400')
                            ->nullable()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->placeholder('camp-2026')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('year')
                            ->label('Annee')
                            ->placeholder('2026')
                            ->required()
                            ->integer()
                            ->minValue(2000)
                            ->maxValue(2100),
                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options(self::statusOptions())
                            ->required()
                            ->native(false)
                            ->default(CampEditionStatus::Draft->value),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Edition active')
                            ->helperText('Une seule edition peut etre active a la fois.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Dates')
                    ->schema([
                        Forms\Components\DateTimePicker::make('registration_open_at')
                            ->label('Ouverture des inscriptions')
                            ->placeholder('Selectionner la date et l heure')
                            ->required()
                            ->seconds(false),
                        Forms\Components\DateTimePicker::make('registration_close_at')
                            ->label('Fermeture des inscriptions')
                            ->placeholder('Selectionner la date et l heure')
                            ->required()
                            ->seconds(false)
                            ->after('registration_open_at'),
                        Forms\Components\DatePicker::make('camp_start_date')
                            ->label('Debut du camp')
                            ->placeholder('Selectionner la date'),
                        Forms\Components\DatePicker::make('camp_end_date')
                            ->label('Fin du camp')
                            ->placeholder('Selectionner la date')
                            ->afterOrEqual('camp_start_date'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Tarification')
                    ->schema([
                        Forms\Components\TextInput::make('currency')
                            ->label('Devise')
                            ->placeholder('XOF')
                            ->required()
                            ->maxLength(10)
                            ->default('XOF'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Description')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Informations internes ou publiques sur cette edition')
                            ->rows(5),
                    ])
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('registration_page_title')
                    ->label('Titre de la page d\'inscription')
                    ->placeholder('Inscription Evenement')
                    ->helperText('Laissez vide pour utiliser le titre par défaut : "Inscription Evenement"')
                    ->nullable()
                    ->maxLength(150)
                    ->columnSpanFull(),
                Forms\Components\Section::make('Champs optionnels du formulaire public')
                    ->description('Activez les champs supplémentaires à afficher lors de l\'inscription')
                    ->schema([
                        Forms\Components\Toggle::make('show_days_presence')
                            ->label('Afficher "Jours de présence" (Jour 1 à Jour 6)')
                            ->default(false),
                        Forms\Components\Toggle::make('show_children_count')
                            ->label('Afficher "Nombre d\'enfants accompagnateurs"')
                            ->default(false),
                        Forms\Components\Toggle::make('show_bus_departure')
                            ->label('Afficher "Départ avec le bus ?"')
                            ->default(false),
                        Forms\Components\Toggle::make('show_participant_type')
                            ->label('Afficher "Vous êtes ?" (Élève / Étudiant / Adulte)')
                            ->default(false),
                    ])
                    ->columnSpanFull(),

                Forms\Components\Section::make('Lien activités du camp')
                    ->description('Affiché sur la page d\'inscription après la description — visible quelle que soit la méthode d\'inscription')
                    ->schema([
                        Forms\Components\TextInput::make('activities_link_label')
                            ->label('Titre du lien')
                            ->placeholder('ex: Programme et activités du camp CIVA 2026')
                            ->nullable()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('activities_link_url')
                            ->label('URL du lien')
                            ->url()
                            ->placeholder('https://...')
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Méthode d\'inscription')
                    ->description('Choisissez comment les participants s\'inscrivent')
                    ->schema([
                        Radio::make('registration_mode')
                            ->label('Mode d\'inscription')
                            ->options([
                                'internal' => 'Formulaire interne (actuel)',
                                'external' => 'Lien externe (Google Form ou autre)',
                            ])
                            ->default('internal')
                            ->inline()
                            ->reactive()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('external_registration_label')
                            ->label('Texte du bouton d\'inscription externe')
                            ->placeholder('ex: S\'inscrire via Google Form')
                            ->nullable()
                            ->columnSpanFull()
                            ->visible(fn (Get $get) => $get('registration_mode') === 'external'),

                        Forms\Components\TextInput::make('external_registration_url')
                            ->label('Lien vers le formulaire externe')
                            ->url()
                            ->placeholder('https://docs.google.com/forms/...')
                            ->nullable()
                            ->columnSpanFull()
                            ->visible(fn (Get $get) => $get('registration_mode') === 'external'),
                    ])
                    ->collapsible(),
                // Section 'Page de Sponsoring' retirée et déplacée vers une Resource dédiée `SponsoringResource`.
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->withCount('registrations')
                ->withCount('editionSections'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('year')
                    ->label('Annee')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (CampEditionStatus|string $state): string => self::statusLabel($state))
                    ->color(fn (CampEditionStatus|string $state): string => match ($state instanceof CampEditionStatus ? $state : CampEditionStatus::from($state)) {
                        CampEditionStatus::Draft => 'gray',
                        CampEditionStatus::Open => 'success',
                        CampEditionStatus::Closed => 'warning',
                        CampEditionStatus::Archived => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_open_at')
                    ->label('Ouverture')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_close_at')
                    ->label('Fermeture')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('registrations_count')
                    ->label('Inscrits')
                    ->counts('registrations')
                    ->sortable(),
                Tables\Columns\IconColumn::make('edition_sections_count')
                    ->label('Sections')
                    ->icon(fn (CampEdition $record): string => ($record->edition_sections_count ?? 0) > 0 ? 'heroicon-o-check-circle' : 'heroicon-o-exclamation-circle')
                    ->color(fn (CampEdition $record): string => ($record->edition_sections_count ?? 0) > 0 ? 'success' : 'warning')
                    ->tooltip(fn (CampEdition $record): string => ($record->edition_sections_count ?? 0) > 0 ? 'Sections definies' : 'Aucune section — formulaire public vide')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options(self::statusOptions()),
                Tables\Filters\SelectFilter::make('year')
                    ->label('Annee')
                    ->options(fn (): array => CampEdition::query()
                        ->orderByDesc('year')
                        ->pluck('year', 'year')
                        ->mapWithKeys(fn (int|string $year, int|string $key): array => [(string) $key => (string) $year])
                        ->all()),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('activate')
                        ->label('Activer')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Activer cette edition ?')
                        ->modalDescription('Toutes les autres editions seront automatiquement desactivees.')
                        ->visible(fn (CampEdition $record): bool => ! $record->is_active)
                        ->action(function (CampEdition $record, CampEditionService $service): void {
                            $service->activateEdition($record);

                            Notification::make()
                                ->title('Edition activee')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('archive')
                        ->label('Archiver')
                        ->icon('heroicon-o-archive-box')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Archiver cette edition ?')
                        ->modalDescription('Les statistiques et inscriptions existantes seront conservees.')
                        ->visible(fn (CampEdition $record): bool => $record->status !== CampEditionStatus::Archived)
                        ->action(function (CampEdition $record, CampEditionService $service): void {
                            $service->archiveEdition($record);

                            Notification::make()
                                ->title('Edition archivee')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('viewRegistrations')
                        ->label('Voir les inscrits')
                        ->icon('heroicon-o-users')
                        ->color('gray')
                        ->modalHeading(fn (CampEdition $record): string => 'Inscrits - ' . $record->name)
                        ->modalDescription(fn (CampEdition $record): string => sprintf(
                            'Cette edition compte %d inscrit(s). La ressource Inscriptions pourra ensuite filtrer cette liste par edition.',
                            $record->registrations_count ?? $record->registrations()->count(),
                        ))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Fermer'),
                    Tables\Actions\EditAction::make()
                        ->label('Modifier'),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Actions')
                    ->color('gray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->label('Supprimer la sélection')
                        ->modalHeading('Supprimer les éléments sélectionnés')
                        ->modalDescription('Cette action est irréversible. Êtes-vous sûr de vouloir supprimer les éléments sélectionnés ?')
                        ->modalSubmitActionLabel('Oui, supprimer'),
                ]),
            ])
            ->defaultSort('year', 'desc');
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            CampEditionStatus::Draft->value => 'Brouillon',
            CampEditionStatus::Open->value => 'Ouverte',
            CampEditionStatus::Closed->value => 'Fermee',
            CampEditionStatus::Archived->value => 'Archivee',
        ];
    }

    public static function statusLabel(CampEditionStatus|string $status): string
    {
        $value = $status instanceof CampEditionStatus ? $status->value : $status;

        return self::statusOptions()[$value] ?? $value;
    }

    /**
     * @return array<int, class-string>
     */
    public static function getRelations(): array
    {
        return [
            RelationManagers\EditionSectionsRelationManager::class,
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampEditions::route('/'),
            'create' => Pages\CreateCampEdition::route('/create'),
            'edit' => Pages\EditCampEdition::route('/{record}/edit'),
        ];
    }
}
