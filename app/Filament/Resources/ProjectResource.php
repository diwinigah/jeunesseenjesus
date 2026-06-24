<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\ProjectStatus;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\InvestorInterestsRelationManager;
use App\Models\Project;
use App\Services\ProjectService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Projets';

    protected static ?string $modelLabel = 'projet';

    protected static ?string $pluralModelLabel = 'projets';

    protected static ?string $navigationLabel = 'Projets a financer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Visuel')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image_path')
                            ->label('Image')
                            ->image()
                            ->disk('public')
                            ->directory('projects')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->imageEditor()
                            ->imagePreviewHeight('150'),
                    ]),

                Forms\Components\Section::make('Informations')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Titre')
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
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('summary')
                            ->label('Description courte')
                            ->maxLength(500)
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('Description détaillée')
                            ->rows(8)
                            ->columnSpanFull()
                            ->nullable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Financement')
                    ->schema([
                        Forms\Components\TextInput::make('funding_goal')
                            ->label('Objectif financier')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01),
                        Forms\Components\TextInput::make('funded_amount')
                            ->label('Montant collecté (F CFA)')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Mis à jour automatiquement lors des confirmations d\'investissement. Modifiable manuellement si besoin.'),
                        Forms\Components\TextInput::make('currency')
                            ->label('Devise')
                            ->required()
                            ->maxLength(10)
                            ->default('XOF'),
                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options(self::statusOptions())
                            ->required()
                            ->native(false)
                            ->default(ProjectStatus::Draft->value),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->latest('created_at'))
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image_path')
                    ->label('Image')
                    ->disk('public')
                    ->height(60)
                    ->width(80)
                    ->defaultImageUrl(url('/images/placeholder.png')),
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (ProjectStatus|string $state): string => self::statusLabel($state))
                    ->color(fn (ProjectStatus|string $state): string => match ($state instanceof ProjectStatus ? $state : ProjectStatus::from($state)) {
                        ProjectStatus::Draft => 'gray',
                        ProjectStatus::Published => 'success',
                        ProjectStatus::Funded => 'info',
                        ProjectStatus::Archived => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('funding_goal')
                    ->label('Objectif')
                    ->money('XOF')
                    ->sortable(),
                Tables\Columns\TextColumn::make('funded_amount')
                    ->label('Collecte')
                    ->money('XOF')
                    ->sortable(),
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progression')
                    ->badge()
                    ->getStateUsing(fn (Project $record): string => number_format(app(ProjectService::class)->getProgressPercentage($record), 2, ',', ' ') . ' %')
                    ->color(fn (Project $record): string => app(ProjectService::class)->getProgressPercentage($record) >= 100 ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Date publication')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('publish')
                        ->label('Publier')
                        ->icon('heroicon-o-megaphone')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (Project $record): bool => $record->status === ProjectStatus::Draft)
                        ->action(function (Project $record, ProjectService $service): void {
                            $service->publishProject($record);

                            Notification::make()
                                ->title('Projet publie')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('archive')
                        ->label('Archiver')
                        ->icon('heroicon-o-archive-box')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->visible(fn (Project $record): bool => $record->status !== ProjectStatus::Archived)
                        ->action(function (Project $record, ProjectService $service): void {
                            $service->archiveProject($record);

                            Notification::make()
                                ->title('Projet archive')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\EditAction::make()
                        ->label('Modifier'),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Actions')
                    ->color('gray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->label('Supprimer la sélection')
                        ->modalHeading('Supprimer les éléments sélectionnés')
                        ->modalDescription('Cette action est irréversible. Êtes-vous sûr de vouloir supprimer les éléments sélectionnés ?')
                        ->modalSubmitActionLabel('Oui, supprimer'),
                ]),
            ]);
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            ProjectStatus::Draft->value => 'Brouillon',
            ProjectStatus::Published->value => 'Publie',
            ProjectStatus::Funded->value => 'Finance',
            ProjectStatus::Archived->value => 'Archive',
        ];
    }

    public static function statusLabel(ProjectStatus|string $status): string
    {
        $value = $status instanceof ProjectStatus ? $status->value : $status;

        return self::statusOptions()[$value] ?? $value;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<string>
     */
    public static function getRelations(): array
    {
        return [
            InvestorInterestsRelationManager::class,
        ];
    }
}
