<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\Gender;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\RegistrationStatus;
use App\Enums\SectionType;
use App\Filament\Exports\RegistrationExporter;
use App\Filament\Resources\RegistrationResource\Pages;
use App\Filament\Resources\RegistrationResource\RelationManagers;
use App\Models\CampEdition;
use App\Models\EditionSection;
use App\Models\Registration;
use App\Services\RegistrationPaymentService;
use App\Services\RegistrationService;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Camp';

    protected static ?string $modelLabel = 'inscription';

    protected static ?string $pluralModelLabel = 'inscriptions';

    protected static ?string $navigationLabel = 'Inscriptions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Resume des paiements')
                    ->description('Affichage des montants de cette inscription')
                    ->schema([
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Montant total')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn (Registration $record): string => number_format((float) $record->total_amount, 2, ',', ' ')),

                        Forms\Components\TextInput::make('paid_amount')
                            ->label('Montant paye')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn (Registration $record): string => number_format((float) $record->paid_amount, 2, ',', ' ')),

                        Forms\Components\TextInput::make('remaining_amount')
                            ->label('Montant restant')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn (Registration $record): string => number_format((float) $record->remaining_amount, 2, ',', ' ')),

                        Forms\Components\Select::make('payment_status')
                            ->label('Statut paiement')
                            ->options(self::paymentStatusOptions())
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn (PaymentStatus|string $state): string => self::paymentStatusLabel($state)),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Edition et section')
                    ->schema([
                        Forms\Components\Select::make('camp_edition_id')
                            ->label('Edition')
                            ->options(fn (): array => CampEdition::query()->orderByDesc('year')->pluck('name', 'id')->all())
                            ->required()
                            ->native(false)
                            ->live(),
                        Forms\Components\Select::make('edition_section_id')
                            ->label('Section')
                            ->helperText('Facultatif — laisser vide si invité')
                            ->options(fn (Get $get): array => self::getEditionSectionOptions((int) $get('camp_edition_id')))
                            ->nullable()
                            ->native(false),
                        Forms\Components\TextInput::make('registration_number')
                            ->label('Numero')
                            ->required()
                            ->maxLength(50),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Participant')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('Prenom')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\Select::make('gender')
                            ->label('Genre')
                            ->options(self::genderOptions())
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telephone')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('whatsapp_phone')
                            ->label('WhatsApp')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('city')
                            ->label('Ville')
                            ->maxLength(150),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Paiement et statut')
                    ->schema([
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Montant total')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make('paid_amount')
                            ->label('Montant paye')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make('remaining_amount')
                            ->label('Montant restant')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn (Registration $record): string => number_format((float) $record->remaining_amount, 2, ',', ' ')),
                        Forms\Components\Select::make('payment_status')
                            ->label('Statut paiement')
                            ->options(self::paymentStatusOptions())
                            ->required()
                            ->native(false),
                        
                        Forms\Components\DateTimePicker::make('submitted_at')
                            ->label('Date de soumission')
                            ->required()
                            ->seconds(false),
                        Forms\Components\DateTimePicker::make('confirmed_at')
                            ->label('Date de confirmation')
                            ->seconds(false),
                    ])
                    ->columns(3),

                
                Forms\Components\Section::make('Informations complémentaires')
                    ->schema([
                        Forms\Components\CheckboxList::make('days_presence')
                            ->label('Jours de présence')
                            ->options([
                                'jour_1' => 'Jour 1',
                                'jour_2' => 'Jour 2',
                                'jour_3' => 'Jour 3',
                                'jour_4' => 'Jour 4',
                                'jour_5' => 'Jour 5',
                                'jour_6' => 'Jour 6',
                            ])
                            ->nullable(),
                        Forms\Components\TextInput::make('children_count')
                            ->label('Nombre d\'enfants accompagnateurs')
                            ->numeric()
                            ->minValue(0)
                            ->nullable(),
                        Forms\Components\Select::make('participant_type')
                            ->label('Vous êtes...')
                            ->options([
                                'eleve'    => 'Élève',
                                'etudiant' => 'Étudiant',
                                'adulte'   => 'Adulte',
                            ])
                            ->nullable(),
                        Forms\Components\Toggle::make('bus_departure')
                            ->label('Départ avec le bus')
                            ->nullable(),
                    ])
                    ->columnSpanFull()
                    ->visible(fn ($record) => $record && (
                        $record->days_presence !== null ||
                        $record->children_count !== null ||
                        $record->bus_departure !== null ||
                        $record->participant_type !== null
                    )),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['campEdition', 'editionSection']))
            ->columns([
                Tables\Columns\TextColumn::make('campEdition.name')
                    ->label('Édition')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->visible(fn ($record, $livewire = null): bool => ($livewire?->getActiveTab() ?? null) === 'archives'),
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('Numero')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Prenom')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('city')
                    ->label('Ville')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Téléphone')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('whatsapp_phone')
                    ->label('WhatsApp')
                    ->copyable()
                    ->icon('heroicon-o-chat-bubble-left')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('days_presence')
                    ->label('Jours')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn ($state): string => is_array($state) ? implode(', ', array_map(fn ($k) => [
                        'jour_1' => 'Jour 1',
                        'jour_2' => 'Jour 2',
                        'jour_3' => 'Jour 3',
                        'jour_4' => 'Jour 4',
                        'jour_5' => 'Jour 5',
                        'jour_6' => 'Jour 6',
                    ][$k] ?? $k, array_keys(array_filter($state)))) : '—'),
                Tables\Columns\TextColumn::make('children_count')
                    ->label('Nbre d\'enfants')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),
                Tables\Columns\IconColumn::make('bus_departure')
                    ->label('Bus')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('editionSection.section')
                    ->label('Section')
                    ->badge()
                    ->formatStateUsing(fn (SectionType|string $state): string => ($state instanceof SectionType ? $state : SectionType::from($state))->label())
                    ->color(fn (SectionType|string $state): string => ($state instanceof SectionType ? $state : SectionType::from($state))->color())
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('remaining_amount')
                    ->label('Montant restant')
                    ->money('XOF')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Paiement')
                    ->badge()
                    ->formatStateUsing(fn (PaymentStatus|string $state): string => self::paymentStatusLabel($state))
                    ->color(fn (PaymentStatus|string $state): string => match ($state instanceof PaymentStatus ? $state : PaymentStatus::from($state)) {
                        PaymentStatus::Unpaid => 'danger',
                        PaymentStatus::Partial => 'warning',
                        PaymentStatus::Paid => 'success',
                    })
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                
                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Soumission')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('camp_edition_id')
                    ->label('Edition')
                    ->relationship('campEdition', 'name'),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Statut paiement')
                    ->options(self::paymentStatusOptions()),
                Tables\Filters\SelectFilter::make('registration_status')
                    ->label('Statut inscription')
                    ->options(self::registrationStatusOptions()),
                Tables\Filters\SelectFilter::make('edition_section_id')
                    ->label('Section')
                    ->relationship('editionSection', 'section')
                    ->getOptionLabelFromRecordUsing(fn (EditionSection $record): string => $record->section->label()),
            ])
            ->actions([
                ActionGroup::make([
                    
                    Tables\Actions\Action::make('confirm_payment')
                        ->label('Paiement confirmé')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Registration $record): bool => $record->payment_status !== PaymentStatus::Paid)
                        ->requiresConfirmation()
                        ->modalHeading('Confirmer le paiement complet ?')
                        ->modalDescription('Le montant restant sera mis à zéro et le statut passera à Payé.')
                        ->action(function (Registration $record, RegistrationPaymentService $service): void {
                            try {
                                $service->recalculateAmounts($record->fresh());
                                $record->update([
                                    'paid_amount' => $record->total_amount,
                                    'remaining_amount' => 0,
                                    'payment_status' => PaymentStatus::Paid,
                                ]);

                                Notification::make()
                                    ->title('Paiement confirmé')
                                    ->success()
                                    ->send();
                            } catch (\InvalidArgumentException $e) {
                                Notification::make()
                                    ->title('Impossible de confirmer')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    Tables\Actions\Action::make('partial_payment')
                        ->label('Paiement partiel')
                        ->icon('heroicon-o-banknotes')
                        ->color('warning')
                        ->visible(fn (Registration $record): bool => $record->payment_status === PaymentStatus::Unpaid)
                        ->form([
                            Forms\Components\TextInput::make('amount')
                                ->label('Montant versé')
                                ->required()
                                ->numeric()
                                ->minValue(0),
                            Forms\Components\Select::make('payment_method')
                                ->label('Méthode')
                                ->options(self::paymentMethodOptions())
                                ->required()
                                ->native(false),
                            Forms\Components\TextInput::make('reference')
                                ->label('Référence')
                                ->nullable(),
                            Forms\Components\DatePicker::make('paid_at')
                                ->label('Date paiement')
                                ->required(),
                        ])
                        ->action(function (Registration $record, array $data, RegistrationPaymentService $service): void {
                            try {
                                $service->addPayment($record, [
                                    'amount' => (float) $data['amount'],
                                    'payment_method' => $data['payment_method'],
                                    'reference' => $data['reference'] ?? null,
                                    'paid_at' => $data['paid_at'],
                                ], auth()->user());

                                Notification::make()
                                    ->title('Paiement partiel enregistré')
                                    ->success()
                                    ->send();
                            } catch (\InvalidArgumentException $e) {
                                Notification::make()
                                    ->title('Montant invalide')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
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
                    Tables\Actions\ExportBulkAction::make()
                        ->label('Exporter Excel')
                        ->exporter(RegistrationExporter::class)
                        ->formats([ExportFormat::Xlsx]),
                ]),
            ])
            ->defaultSort('submitted_at', 'desc');
    }

    /**
     * @return array<string, string>
     */
    public static function genderOptions(): array
    {
        return [
            Gender::Male->value => 'Homme',
            Gender::Female->value => 'Femme',
            Gender::Other->value => 'Autre',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function paymentMethodOptions(): array
    {
        return [
            PaymentMethod::Cash->value => 'Espèces',
            PaymentMethod::MobileMoney->value => 'Mobile Money',
            PaymentMethod::BankTransfer->value => 'Virement bancaire',
            PaymentMethod::Cheque->value => 'Chèque',
            PaymentMethod::Other->value => 'Autre',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function paymentStatusOptions(): array
    {
        return [
            PaymentStatus::Unpaid->value => 'Non paye',
            PaymentStatus::Partial->value => 'Partiel',
            PaymentStatus::Paid->value => 'Paye',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function registrationStatusOptions(): array
    {
        return [
            RegistrationStatus::Pending->value => 'En attente',
            RegistrationStatus::Confirmed->value => 'Confirmee',
            RegistrationStatus::Cancelled->value => 'Annulee',
        ];
    }

    public static function paymentStatusLabel(PaymentStatus|string $status): string
    {
        $value = $status instanceof PaymentStatus ? $status->value : $status;

        return self::paymentStatusOptions()[$value] ?? $value;
    }

    public static function registrationStatusLabel(RegistrationStatus|string $status): string
    {
        $value = $status instanceof RegistrationStatus ? $status->value : $status;

        return self::registrationStatusOptions()[$value] ?? $value;
    }

    /**
     * @return array<int, class-string>
     */
    public static function getRelations(): array
    {
        return [
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function getEditionSectionOptions(int $campEditionId): array
    {
        $options = [];
        $sections = EditionSection::query()
            ->where('camp_edition_id', $campEditionId)
            ->orderBy('section')
            ->get();

        foreach ($sections as $section) {
            $options[$section->getKey()] = $section->section->label();
        }

        return $options;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $activeEdition = CampEdition::where('is_active', true)->first();

        $query = parent::getEloquentQuery();

        if ($activeEdition) {
            $query = $query->where('camp_edition_id', $activeEdition->id);
        }

        return $query->with(['campEdition', 'editionSection']);
    }

    
}
