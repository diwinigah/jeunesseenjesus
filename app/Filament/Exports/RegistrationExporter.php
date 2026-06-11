<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Enums\PaymentStatus;
use App\Enums\RegistrationStatus;
use App\Models\Registration;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class RegistrationExporter extends Exporter
{
    protected static ?string $model = Registration::class;

    /**
     * @return array<int, ExportColumn>
     */
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('registration_number')
                ->label('Numero'),
            ExportColumn::make('first_name')
                ->label('Prenom'),
            ExportColumn::make('last_name')
                ->label('Nom'),
            ExportColumn::make('city')
                ->label('Ville'),
            ExportColumn::make('phone')
                ->label('Téléphone'),
            ExportColumn::make('whatsapp_phone')
                ->label('WhatsApp'),
            ExportColumn::make('campEdition.name')
                ->label('Edition'),
            ExportColumn::make('editionSection.section')
                ->label('Section')
                ->formatStateUsing(fn (mixed $state): string => is_object($state) && method_exists($state, 'label') ? $state->label() : (string) $state),
            ExportColumn::make('total_amount')
                ->label('Montant total'),
            ExportColumn::make('paid_amount')
                ->label('Montant paye'),
            ExportColumn::make('remaining_amount')
                ->label('Montant restant'),
            ExportColumn::make('payment_status')
                ->label('Statut paiement')
                ->formatStateUsing(fn (PaymentStatus|string $state): string => self::paymentStatusLabel($state)),
            ExportColumn::make('registration_status')
                ->label('Statut inscription')
                ->formatStateUsing(fn (RegistrationStatus|string $state): string => self::registrationStatusLabel($state)),
            ExportColumn::make('submitted_at')
                ->label('Date soumission'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return sprintf(
            '%d inscription(s) exportee(s) avec succes.',
            $export->successful_rows,
        );
    }

    private static function paymentStatusLabel(PaymentStatus|string $status): string
    {
        $value = $status instanceof PaymentStatus ? $status->value : $status;

        return [
            PaymentStatus::Unpaid->value => 'Non paye',
            PaymentStatus::Partial->value => 'Partiel',
            PaymentStatus::Paid->value => 'Paye',
        ][$value] ?? $value;
    }

    private static function registrationStatusLabel(RegistrationStatus|string $status): string
    {
        $value = $status instanceof RegistrationStatus ? $status->value : $status;

        return [
            RegistrationStatus::Pending->value => 'En attente',
            RegistrationStatus::Confirmed->value => 'Confirmee',
            RegistrationStatus::Cancelled->value => 'Annulee',
        ][$value] ?? $value;
    }
}
