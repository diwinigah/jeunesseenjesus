<?php

declare(strict_types=1);

namespace App\Filament\Resources\CampEditionResource\RelationManagers;

use App\Enums\SectionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EditionSectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'editionSections';

    protected static ?string $title = 'Sections et tarifs';

    protected static ?string $modelLabel = 'section';

    protected static ?string $pluralModelLabel = 'sections';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('section')
                    ->label('Section')
                    ->placeholder('Choisir une section')
                    ->options(self::sectionOptions())
                    ->required()
                    ->native(false)
                    ->unique(
                        table: 'edition_sections',
                        column: 'section',
                        ignoreRecord: true,
                        modifyRuleUsing: fn ($rule) => $rule->where('camp_edition_id', $this->getOwnerRecord()->getKey()),
                    ),
                Forms\Components\TextInput::make('price')
                    ->label('Tarif')
                    ->placeholder('0.00')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\TextInput::make('description')
                    ->label('Description')
                    ->placeholder('Exemple : tarif famille avec reduction par tete')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withCount('registrations'))
            ->columns([
                Tables\Columns\TextColumn::make('section')
                    ->label('Section')
                    ->badge()
                    ->formatStateUsing(fn (SectionType|string $state): string => self::sectionLabel($state))
                    ->color(fn (SectionType|string $state): string => ($state instanceof SectionType ? $state : SectionType::from($state))->color())
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Tarif')
                    ->money((string) $this->getOwnerRecord()->currency)
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registrations_count')
                    ->label('Inscrits')
                    ->counts('registrations')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter une section'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Modifier'),
                Tables\Actions\DeleteAction::make()
                    ->label('Supprimer'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Supprimer'),
                ]),
            ]);
    }

    /**
     * @return array<string, string>
     */
    private static function sectionOptions(): array
    {
        return collect(SectionType::cases())
            ->mapWithKeys(fn (SectionType $section): array => [$section->value => $section->label()])
            ->all();
    }

    private static function sectionLabel(SectionType|string $section): string
    {
        return ($section instanceof SectionType ? $section : SectionType::from($section))->label();
    }
}
