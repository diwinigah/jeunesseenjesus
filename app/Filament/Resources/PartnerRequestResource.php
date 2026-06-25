<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PartnerRequestStatus;
use App\Filament\Resources\PartnerRequestResource\Pages;
use App\Models\PartnerRequest;
use App\Services\PartnerService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartnerRequestResource extends Resource
{
    protected static ?string $model = PartnerRequest::class;

    protected static ?string $slug = 'partner-requests';

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationLabel = 'Demandes de partenariat';

    protected static ?string $modelLabel = 'Demande de partenariat';

    protected static ?string $pluralModelLabel = 'Demandes de partenariat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informations')
                    ->schema([
                        TextInput::make('contact_name')
                            ->label('Nom du contact')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('organization_name')
                            ->label('Nom de l\'organisation')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),

                        TextInput::make('phone')
                            ->label('Téléphone')
                            ->tel()
                            ->required()
                            ->maxLength(50),

                        Select::make('type')
                            ->label('Type')
                            ->options([
                                'church' => 'Église',
                                'company' => 'Entreprise',
                                'association' => 'Association',
                                'individual' => 'Individu',
                                'other' => 'Autre',
                            ])
                            ->required(),

                        TextInput::make('website_url')
                            ->label('Site web')
                            ->url()
                            ->maxLength(500),
                    ])
                    ->columns(2),

                Section::make('Visuel')
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('Logo / Photo')
                            ->disk('public')
                            ->directory('partners/requests')
                            ->image()
                            ->imagePreviewHeight('150')
                            ->maxSize(2048)
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp'
                            ])
                            ->nullable(),
                    ]),

                Section::make('Message')
                    ->schema([
                        Textarea::make('message')
                            ->label('Message')
                            ->rows(4)
                            ->maxLength(5000),
                    ]),

                Section::make('Administration')
                    ->schema([
                        Textarea::make('admin_notes')
                            ->label('Notes internes')
                            ->rows(4),

                        Select::make('status')
                            ->label('Statut')
                            ->options(
                                collect(
                                    \App\Enums\PartnerRequestStatus::cases()
                                )
                                ->mapWithKeys(fn ($case) => [
                                    $case->value => $case->label()
                                ])->toArray()
                            )
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contact_name')
                    ->label('Contact')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('organization_name')
                    ->label('Organisation')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Téléphone')
                    ->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('email')
                    ->label('Email')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

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

                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn ($state) =>
                        $state instanceof \App\Enums\PartnerRequestStatus
                            ? $state->label()
                            : ($state ?? '—')
                    )
                    ->color(fn ($state) =>
                        $state instanceof \App\Enums\PartnerRequestStatus
                            ? $state->color()
                            : 'gray'
                    )
                    ->sortable(),

                TextColumn::make('submitted_at')
                    ->label('Soumise le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        PartnerRequestStatus::New->value => 'Nouvelle',
                        PartnerRequestStatus::Accepted->value => 'Acceptée',
                        PartnerRequestStatus::Rejected->value => 'Rejetée',
                    ]),

                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'church' => 'Église',
                        'company' => 'Entreprise',
                        'association' => 'Association',
                        'individual' => 'Individu',
                        'other' => 'Autre',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),

                    // Accepter
                    \Filament\Tables\Actions\Action::make('accept')
                        ->label('Accepter')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function (PartnerRequest $record): void {
                            $service = new PartnerService();
                            $service->approveRequest($record);

                            Notification::make()
                                ->title('Demande acceptée')
                                ->body('La demande a été acceptée.')
                                ->success()
                                ->send();
                        })
                        ->hidden(fn (PartnerRequest $record) => $record->status !== PartnerRequestStatus::Accepted),

                    // Rejeter
                    \Filament\Tables\Actions\Action::make('reject')
                        ->label('Rejeter')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (PartnerRequest $record): void {
                            $service = new PartnerService();
                            $service->rejectRequest($record);

                            Notification::make()
                                ->title('Demande rejetée')
                                ->body('La demande a été rejetée.')
                                ->warning()
                                ->send();
                        })
                        ->hidden(fn (PartnerRequest $record) => $record->status !== PartnerRequestStatus::New),

                    // Convertir en partenaire
                    \Filament\Tables\Actions\Action::make('convert')
                        ->label('Convertir en partenaire')
                        ->icon('heroicon-o-arrow-right')
                        ->color('primary')
                        ->action(function (PartnerRequest $record): void {
                            $service = new PartnerService();
                            $service->convertToPartner($record);

                            Notification::make()
                                ->title('Partenaire créé')
                                ->body('La demande a été convertie en partenaire.')
                                ->success()
                                ->send();
                        })
                        ->visible(fn (PartnerRequest $record) => $record->status === PartnerRequestStatus::Accepted && $record->converted_partner_id === null),
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
            ->defaultSort('submitted_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerRequests::route('/'),
            'edit' => Pages\EditPartnerRequest::route('/{record}/edit'),
        ];
    }
}
