<?php

declare(strict_types=1);

namespace App\Filament\Resources\CampEditionResource\Pages;

use App\Enums\SectionType;
use App\Filament\Resources\CampEditionResource;
use App\Models\CampEdition;
use App\Services\CampEditionService;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateCampEdition extends CreateRecord
{
    protected static string $resource = CampEditionResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    self::getInformationsStep(),
                    self::getSectionsStep(),
                ])
                ->columnSpanFull(),
            ]);
    }

    public static function getInformationsStep(): Forms\Components\Wizard\Step
    {
        return Forms\Components\Wizard\Step::make('Informations de l\'edition')
            ->schema([
                Forms\Components\Section::make('Generales')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom de l\'edition')
                            ->placeholder('Exemple : Camp 2026')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Forms\Set $set): void {
                                if ($state !== null) {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->placeholder('camp-2026')
                            ->required()
                            ->maxLength(255)
                            ->unique('camp_editions', 'slug'),
                        Forms\Components\TextInput::make('year')
                            ->label('Annee')
                            ->placeholder('2026')
                            ->required()
                            ->integer()
                            ->minValue(2000)
                            ->maxValue(2100),
                    ])
                    ->columns(3),

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

                Forms\Components\Section::make('Configuration')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options(CampEditionResource::statusOptions())
                            ->required()
                            ->native(false)
                            ->default('draft'),
                    ]),

                Forms\Components\Section::make('Description')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Informations internes ou publiques sur cette edition')
                            ->rows(5),
                    ]),
            ]);
    }

    public static function getSectionsStep(): Forms\Components\Wizard\Step
    {
        return Forms\Components\Wizard\Step::make('Sections et tarifs')
            ->description('Definir les tarifs pour chaque section')
            ->schema([
                Toggle::make('copy_previous_sections')
                    ->label('Conserver les sections de l\'édition précédente')
                    ->helperText('Activez pour pré-remplir les sections avec la dernière édition. Vous pourrez les modifier.')
                    ->default(false)
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set): void {
                        if ($state) {
                            $lastEdition = CampEdition::latest()
                                ->with('editionSections')
                                ->first();
                            if ($lastEdition && $lastEdition->editionSections->isNotEmpty()) {
                                $sections = $lastEdition->editionSections
                                    ->map(fn ($s) => [
                                        'section' => $s->section instanceof \BackedEnum ? $s->section->value : $s->section,
                                        'price' => $s->price,
                                        'description' => $s->description,
                                    ])->toArray();
                                $set('sections', $sections);
                            }
                        } else {
                            $set('sections', []);
                        }
                    }),

                Forms\Components\Repeater::make('sections')
                    ->label('Sections et tarifs')
                    ->minItems(0)
                    ->defaultItems(0)
                    ->disabled(false)
                    ->schema([
                        Forms\Components\TextInput::make('section')
                            ->label('Section')
                            ->required()
                            ->datalist(
                                collect(SectionType::cases())
                                    ->map(fn ($case) => $case->value)
                                    ->toArray()
                            )
                            ->placeholder('Choisir une section')
                            ->helperText('Sélectionnez une section existante'),

                        // Champ 'price' retire — le tarif n'est plus saisi dans le wizard public.

                        Forms\Components\TextInput::make('description')
                            ->label('Description')
                            ->placeholder('Tarif reduit, special famille, etc.')
                            ->maxLength(255),
                    ])
                    ->columns(3)
                    ->reorderableWithButtons(),
            ]);
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $activeEdition = null;
        
        // Remove copy_previous_sections field (virtual, not saved to DB)
        unset($data['copy_previous_sections']);
        
        // Vérifier si une édition active existe déjà
        if (! empty($data['is_active']) && $data['is_active'] === true) {
            $activeEdition = CampEdition::where('is_active', true)->first();

            if ($activeEdition) {
                Notification::make()
                    ->title('Édition active existante')
                    ->body('L\'édition "' . $activeEdition->name . '" est déjà active. Désactivez-la avant d\'en créer une nouvelle active.')
                    ->danger()
                    ->persistent()
                    ->send();

                $this->halt();

                return $activeEdition;
            }
        }

        $sectionsData = [];

        // Get valid section values from enum
        $validSectionValues = collect(SectionType::cases())
            ->map(fn ($case) => $case->value)
            ->toArray();

        // Transform sections data for createWithSections()
                if (! empty($data['sections']) && is_array($data['sections'])) {
            foreach ($data['sections'] as $section) {
                // Validate that section value is in the enum
                $sectionValue = $section['section'] ?? null;
                if (! in_array($sectionValue, $validSectionValues)) {
                    Notification::make()
                        ->title('Section invalide')
                        ->body("La section '{$sectionValue}' n'existe pas. Utilisez une section valide.")
                        ->danger()
                        ->persistent()
                        ->send();
                    $this->halt();
                    return new CampEdition();
                }

                $sectionsData[$sectionValue] = [
                    'price' => isset($section['price']) ? (float) $section['price'] : null,
                    'description' => $section['description'] ?? null,
                ];
            }
        }

        // Remove sections from data before passing to service
        unset($data['sections']);

        // Handle empty sections - require at least one
        if (empty($sectionsData)) {
            Notification::make()
                ->title('Aucune section')
                ->body('Vous devez ajouter au moins une section.')
                ->danger()
                ->persistent()
                ->send();
            $this->halt();
            return new CampEdition();
        }

        // Set currency to XOF (default)
        $data['currency'] = 'XOF';

        // Create edition with sections in transaction
        return app(CampEditionService::class)->createWithSections($data, $sectionsData);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Edition creee avec sections';
    }

    protected function getRedirectUrl(): string
    {
        /** @var CampEdition $record */
        $record = $this->getRecord();

        return static::getResource()::getUrl('edit', ['record' => $record]);
    }
}


