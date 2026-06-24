<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PartnerStatus;
use App\Filament\Resources\PartnerResource\Pages;
use App\Models\Partner;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $slug = 'partners';

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Partenaires';

    protected static ?string $modelLabel = 'Partenaire';

    protected static ?string $pluralModelLabel = 'Partenaires';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identité')
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('partners')
                            ->maxSize(5120),

                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set): void {
                                if ($state !== null) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique('partners', 'slug', ignoreRecord: true),

                        Select::make('type')
                            ->label('Type')
                            ->options([
                                'church' => 'Église',
                                'company' => 'Entreprise',
                                'association' => 'Association',
                                'individual' => 'Individu',
                                'other' => 'Autre',
                            ])
                            ->native(false),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->maxLength(2000),
                    ])
                    ->columns(2),

                Section::make('Contact')
                    ->schema([
                        TextInput::make('website_url')
                            ->label('Site web')
                            ->url()
                            ->maxLength(500),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Téléphone')
                            ->tel()
                            ->maxLength(20),
                    ])
                    ->columns(2),

                Section::make('Affichage')
                    ->schema([
                        Toggle::make('is_public')
                            ->label('Public')
                            ->helperText('Visible sur le site public'),

                        TextInput::make('display_order')
                            ->label('Ordre d\'affichage')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        Select::make('status')
                            ->label('Statut')
                            ->options([
                                PartnerStatus::Active->value => 'Actif',
                                PartnerStatus::Inactive->value => 'Inactif',
                                PartnerStatus::Archived->value => 'Archivé',
                            ])
                            ->native(false)
                            ->default(PartnerStatus::Active->value),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public'),

                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) =>
                        $state instanceof \App\Enums\PartnerType
                            ? $state->label()
                            : ($state ?? '—')
                    )
                    ->color(fn ($state) =>
                        $state instanceof \App\Enums\PartnerType
                            ? $state->color()
                            : 'gray'
                    ),

                TextColumn::make('website_url')
                    ->label('Site web')
                    ->url(fn ($record) => $record->website_url)
                    ->openUrlInNewTab()
                    ->limit(30),

                IconColumn::make('is_public')
                    ->label('Public')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn ($state) =>
                        $state instanceof \App\Enums\PartnerStatus
                            ? $state->label()
                            : ($state ?? '—')
                    )
                    ->color(fn ($state) =>
                        $state instanceof \App\Enums\PartnerStatus
                            ? $state->color()
                            : 'gray'
                    )
                    ->sortable(),

                TextColumn::make('display_order')
                    ->label('Ordre')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->label('Supprimer la sélection')
                        ->modalHeading('Supprimer les éléments sélectionnés')
                        ->modalDescription('Cette action est irréversible. Êtes-vous sûr de vouloir supprimer les éléments sélectionnés ?')
                        ->modalSubmitActionLabel('Oui, supprimer'),
                ]),
            ])
            ->defaultSort('display_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
