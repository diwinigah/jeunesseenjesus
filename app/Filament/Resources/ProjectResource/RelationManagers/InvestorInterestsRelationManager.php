<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Enums\ProjectInvestorInterestStatus;
use App\Models\InvestorUser;
use App\Models\ProjectInvestorInterest;
use App\Services\ProjectService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InvestorInterestsRelationManager extends RelationManager
{
    protected static string $relationship = 'projectInvestorInterests';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
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
                            ->hidden(fn (Get $get) => !$get('has_account')),

                        Forms\Components\TextInput::make('manual_name')
                            ->label('Nom complet')
                            ->required()
                            ->hidden(fn (Get $get) => $get('has_account')),

                        Forms\Components\TextInput::make('manual_organisation')
                            ->label('Organisation')
                            ->nullable()
                            ->hidden(fn (Get $get) => $get('has_account')),

                        Forms\Components\TextInput::make('manual_email')
                            ->label('Email')
                            ->email()
                            ->nullable()
                            ->hidden(fn (Get $get) => $get('has_account')),

                        Forms\Components\TextInput::make('manual_phone')
                            ->label('Téléphone')
                            ->nullable()
                            ->hidden(fn (Get $get) => $get('has_account')),
                    ]),

                Forms\Components\Section::make('Proposition')
                    ->schema([
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
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('investor_name')
                    ->label('Nom investisseur')
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
                Tables\Columns\TextColumn::make('intended_amount')
                    ->label('Montant proposé')
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('confirm')
                        ->label('Confirmer')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (ProjectInvestorInterest $record) => $record->status !== ProjectInvestorInterestStatus::Pledged)
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
                        ->visible(fn (ProjectInvestorInterest $record) => $record->status !== ProjectInvestorInterestStatus::Cancelled)
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
                    Tables\Actions\DeleteAction::make()
                        ->label('Supprimer'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
