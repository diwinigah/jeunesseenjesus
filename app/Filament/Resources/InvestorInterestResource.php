<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\ProjectInvestorInterestStatus;
use App\Filament\Resources\InvestorInterestResource\Pages;
use App\Models\InvestorUser;
use App\Models\ProjectInvestorInterest;
use App\Services\ProjectService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InvestorInterestResource extends Resource
{
    protected static ?string $model = ProjectInvestorInterest::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Financement';

    protected static ?string $modelLabel = 'proposition d\'investissement';

    protected static ?string $pluralModelLabel = 'propositions d\'investissement';

    protected static ?string $navigationLabel = 'Propositions d\'investissement';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Investisseur')
                    ->schema([
                        Forms\Components\Toggle::make('has_account')
                            ->label('Investisseur avec compte')
                            ->default(fn (?ProjectInvestorInterest $record) => $record?->investor_user_id !== null)
                            ->live(),

                        Forms\Components\Select::make('investor_user_id')
                            ->label('Choisir l\'investisseur')
                            ->options(InvestorUser::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->hidden(fn (Get $get) => !$get('has_account'))
                            ->disabled(fn (string $operation) => $operation === 'edit'),

                        Forms\Components\TextInput::make('manual_name')
                            ->label('Nom complet')
                            ->required()
                            ->hidden(fn (Get $get) => $get('has_account'))
                            ->disabled(fn (string $operation) => $operation === 'edit'),

                        Forms\Components\TextInput::make('manual_organisation')
                            ->label('Organisation')
                            ->nullable()
                            ->hidden(fn (Get $get) => $get('has_account'))
                            ->disabled(fn (string $operation) => $operation === 'edit'),

                        Forms\Components\TextInput::make('manual_email')
                            ->label('Email')
                            ->email()
                            ->nullable()
                            ->hidden(fn (Get $get) => $get('has_account'))
                            ->disabled(fn (string $operation) => $operation === 'edit'),

                        Forms\Components\TextInput::make('manual_phone')
                            ->label('Téléphone')
                            ->nullable()
                            ->hidden(fn (Get $get) => $get('has_account'))
                            ->disabled(fn (string $operation) => $operation === 'edit'),
                    ]),

                Forms\Components\Section::make('Proposition')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label('Projet *')
                            ->relationship('project', 'title', fn (Builder $query) => $query->where('status', 'published'))
                            ->required()
                            ->native(false)
                            ->disabled(fn (string $operation) => $operation === 'edit'),

                        Forms\Components\TextInput::make('intended_amount')
                            ->label('Montant proposé (F CFA) *')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        Forms\Components\Textarea::make('message')
                            ->label('Message')
                            ->rows(3)
                            ->nullable(),

                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'new' => 'Nouvelle',
                                'contacted' => 'Contacté',
                                'pledged' => 'Engagé',
                                'paid' => 'Payé',
                                'cancelled' => 'Annulé',
                            ])
                            ->default('new')
                            ->native(false),

                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Notes administrateur')
                            ->rows(4)
                            ->nullable()
                            ->visible(fn (string $operation) => $operation === 'edit'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['investorUser', 'project'])->latest('created_at'))
            ->columns([
                Tables\Columns\TextColumn::make('investor_name')
                    ->label('Investisseur')
                    ->searchable(
                        query: function (Builder $query, string $search): Builder {
                            return $query
                                ->whereHas('investorUser',
                                    fn ($q) => $q->where('name', 'like', "%{$search}%"))
                                ->orWhere('manual_name', 'like', "%{$search}%");
                        }
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('investorUser.organization_name')
                    ->label('Organisation')
                    ->sortable(),
                Tables\Columns\TextColumn::make('investor_email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('investor_phone')
                    ->label('Téléphone')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('project.title')
                    ->label('Projet')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('intended_amount')
                    ->label('Montant')
                    ->money('XOF')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->formatStateUsing(fn (ProjectInvestorInterestStatus|string $state): string => match ($state instanceof ProjectInvestorInterestStatus ? $state : ProjectInvestorInterestStatus::from($state)) {
                        ProjectInvestorInterestStatus::New => 'Nouvelle',
                        ProjectInvestorInterestStatus::Contacted => 'Contacté',
                        ProjectInvestorInterestStatus::Pledged => 'Engagé',
                        ProjectInvestorInterestStatus::Paid => 'Payé',
                        ProjectInvestorInterestStatus::Cancelled => 'Annulé',
                    })
                    ->color(fn (ProjectInvestorInterestStatus|string $state): string => match ($state instanceof ProjectInvestorInterestStatus ? $state : ProjectInvestorInterestStatus::from($state)) {
                        ProjectInvestorInterestStatus::New => 'info',
                        ProjectInvestorInterestStatus::Contacted => 'warning',
                        ProjectInvestorInterestStatus::Pledged => 'success',
                        ProjectInvestorInterestStatus::Paid => 'success',
                        ProjectInvestorInterestStatus::Cancelled => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date soumission')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project_id')
                    ->label('Projet')
                    ->relationship('project', 'title'),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'new' => 'Nouvelle',
                        'contacted' => 'Contacté',
                        'pledged' => 'Engagé',
                        'paid' => 'Payé',
                        'cancelled' => 'Annulé',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->label('Modifier'),
                    Tables\Actions\Action::make('confirm')
                        ->label('Confirmer')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (ProjectInvestorInterest $record): void {
                            $record->update(['status' => ProjectInvestorInterestStatus::Pledged]);

                            app(ProjectService::class)->updateFundedAmount($record->project);

                            Notification::make()
                                ->title('Proposition confirmée')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('reject')
                        ->label('Rejeter')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (ProjectInvestorInterest $record): void {
                            $record->update(['status' => ProjectInvestorInterestStatus::Cancelled]);

                            app(ProjectService::class)->updateFundedAmount($record->project);

                            Notification::make()
                                ->title('Proposition rejetée')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('record_payment')
                        ->label('Enregistrer paiement')
                        ->icon('heroicon-o-banknotes')
                        ->color('success')
                        ->visible(fn (ProjectInvestorInterest $record) => $record->status === ProjectInvestorInterestStatus::Pledged)
                        ->form([
                            Forms\Components\TextInput::make('committed_amount')
                                ->label('Montant payé (F CFA)')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->default(fn (ProjectInvestorInterest $record) => $record->intended_amount),
                            Forms\Components\DatePicker::make('paid_at')
                                ->label('Date du paiement')
                                ->required()
                                ->default(now()),
                            Forms\Components\Textarea::make('payment_notes')
                                ->label('Notes')
                                ->rows(3)
                                ->nullable(),
                        ])
                        ->action(function (ProjectInvestorInterest $record, array $data): void {
                            $record->update([
                                'committed_amount' => $data['committed_amount'],
                                'status' => ProjectInvestorInterestStatus::Paid,
                            ]);

                            app(ProjectService::class)->updateFundedAmount($record->project);

                            Notification::make()
                                ->title('Paiement enregistré')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Supprimer'),
                ]),
            ]);
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvestorInterests::route('/'),
            'edit' => Pages\EditInvestorInterest::route('/{record}/edit'),
        ];
    }
}
