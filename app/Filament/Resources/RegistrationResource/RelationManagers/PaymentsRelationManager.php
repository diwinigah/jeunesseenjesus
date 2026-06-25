<?php

declare(strict_types=1);

namespace App\Filament\Resources\RegistrationResource\RelationManagers;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Registration;
use App\Models\RegistrationPayment;
use App\Services\RegistrationPaymentService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Paiements';

    protected static ?string $modelLabel = 'paiement';

    protected static ?string $pluralModelLabel = 'paiements';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->label('Montant')
                    ->placeholder('0.00')
                    ->required()
                    ->numeric()
                    ->minValue(0.01)
                    ->maxValue(fn (): float => (float) ($this->getOwnerRecord()?->remaining_amount ?? 0))
                    ->step(0.01)
                    ->helperText(fn (): string => sprintf(
                        'Montant restant : %s',
                        number_format((float) ($this->getOwnerRecord()?->remaining_amount ?? 0), 2, ',', ' '),
                    )),

                Forms\Components\Select::make('payment_method')
                    ->label('Methode de paiement')
                    ->options(self::paymentMethodOptions())
                    ->required()
                    ->native(false),

                Forms\Components\TextInput::make('reference')
                    ->label('Reference')
                    ->placeholder('Ex: TXN123456, Cheque 001, etc.')
                    ->maxLength(255),

                Forms\Components\DateTimePicker::make('paid_at')
                    ->label('Date du paiement')
                    ->placeholder('Selectionner la date et l heure')
                    ->required()
                    ->seconds(false),

                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->placeholder('Informations supplementaires sur le paiement')
                    ->rows(3)
                    ->maxLength(1000),
            ]);
    }

    public function table(Table $table): Table
    {
        /** @var Registration $registration */
        $registration = $this->getOwnerRecord();

        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('validator'))
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->money((string) $registration->campEdition->currency)
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Methode')
                    ->badge()
                    ->formatStateUsing(fn (PaymentMethod|string $state): string => self::paymentMethodLabel($state))
                    ->color(fn (PaymentMethod|string $state): string => ($state instanceof PaymentMethod ? $state : PaymentMethod::from($state)) === PaymentMethod::Cash ? 'success' : 'info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Date du paiement')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('validator.name')
                    ->label('Valide par')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Enregistre le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Methode')
                    ->options(self::paymentMethodOptions()),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter un paiement')
                    ->form(fn (Form $form): Form => $this->form($form))
                    ->action(function (array $data, RegistrationPaymentService $service): void {
                        try {
                            $service->addPayment($this->getOwnerRecord(), $data, auth()->user());

                            Notification::make()
                                ->title('Paiement enregistre')
                                ->body(sprintf(
                                    'Montant : %s',
                                    number_format((float) $data['amount'], 2, ',', ' '),
                                ))
                                ->success()
                                ->send();
                        } catch (\InvalidArgumentException $e) {
                            Notification::make()
                                ->title('Erreur')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->label('Supprimer')
                    ->before(function (RegistrationPayment $record, RegistrationPaymentService $service): void {
                        $service->deletePayment($record);
                    })
                    ->successNotificationTitle('Paiement supprime'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Supprimer'),
                ]),
            ])
            ->defaultSort('paid_at', 'desc');
    }

    /**
     * @return array<string, string>
     */
    private static function paymentMethodOptions(): array
    {
        return [
            PaymentMethod::Cash->value => 'Especes',
            PaymentMethod::MobileMoney->value => 'Mobile Money',
            PaymentMethod::BankTransfer->value => 'Virement bancaire',
            PaymentMethod::Cheque->value => 'Cheque',
            PaymentMethod::Other->value => 'Autre',
        ];
    }

    private static function paymentMethodLabel(PaymentMethod|string $method): string
    {
        $value = $method instanceof PaymentMethod ? $method->value : $method;

        return self::paymentMethodOptions()[$value] ?? $value;
    }
}
