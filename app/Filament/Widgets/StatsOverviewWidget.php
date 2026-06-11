<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Registration;
use App\Services\CampEditionService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $campEditionService = app(CampEditionService::class);
        $edition = $campEditionService->getCurrentActiveEdition();

        if ($edition === null) {
            return [
                Stat::make('Aucune édition', 'Aucune édition active actuellement')
                    ->icon('heroicon-o-exclamation-circle')
                    ->color('warning'),
            ];
        }

        // Statistiques d'inscrits
        $totalRegistrations = Registration::byEdition($edition)
            ->count();

        $paidRegistrations = Registration::byEdition($edition)
            ->byPaymentStatus(PaymentStatus::Paid)
            ->count();

        $unpaidRegistrations = Registration::byEdition($edition)
            ->byPaymentStatus(PaymentStatus::Unpaid)
            ->count();

        // Statistiques de paiements
        $totalPaidAmount = (float) Registration::byEdition($edition)
            ->sum('paid_amount');

        $totalRemainingAmount = (float) Registration::byEdition($edition)
            ->sum('remaining_amount');

        return [
            Stat::make('Inscrits total', (string) $totalRegistrations)
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Paiements validés', (string) $paidRegistrations)
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Paiements en attente', (string) $unpaidRegistrations)
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make(
                'Montant collecté',
                number_format($totalPaidAmount, 0, ',', ' ') . ' F CFA'
            )
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make(
                'Montant restant',
                number_format($totalRemainingAmount, 0, ',', ' ') . ' F CFA'
            )
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger'),

            Stat::make(
                'Édition active',
                $edition->name . ' (' . $edition->year . ')'
            )
                ->description(
                    'Du ' . $edition->registration_open_at->format('d/m/Y') .
                    ' au ' . $edition->registration_close_at->format('d/m/Y')
                )
                ->icon('heroicon-o-calendar')
                ->color('info'),
        ];
    }
}
