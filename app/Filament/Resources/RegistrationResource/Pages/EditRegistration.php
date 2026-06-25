<?php

declare(strict_types=1);

namespace App\Filament\Resources\RegistrationResource\Pages;

use App\Enums\PaymentStatus;
use App\Filament\Resources\RegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistration extends EditRecord
{
    protected static string $resource = RegistrationResource::class;

    /**
     * @return array<int, Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Supprimer'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Inscription mise a jour';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // S'assurer que remaining_amount est calculé avant sauvegarde
        $total = (float) ($data['total_amount'] ?? 0);
        $paid  = (float) ($data['paid_amount'] ?? 0);

        $data['remaining_amount'] = max(0, $total - $paid);

        // Calculer payment_status
        if ($paid <= 0 || $total <= 0) {
            $data['payment_status'] = PaymentStatus::Unpaid->value;
        } elseif ($paid >= $total) {
            $data['payment_status'] = PaymentStatus::Paid->value;
        } else {
            $data['payment_status'] = PaymentStatus::Partial->value;
        }

        return $data;
    }
}
