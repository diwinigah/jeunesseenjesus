<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Registration;
use App\Models\RegistrationPayment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegistrationPaymentService
{
    /**
     * Add a payment to a registration and update amounts.
     *
     * @param array<string, mixed> $data Payment data (amount, payment_method, reference, paid_at, notes)
     *
     * @throws \InvalidArgumentException If amount exceeds remaining_amount
     */
    public function addPayment(Registration $registration, array $data, User $admin): RegistrationPayment
    {
        return DB::transaction(function () use ($registration, $data, $admin): RegistrationPayment {
            // Lock the registration record for consistency
            /** @var Registration $lockedRegistration */
            $lockedRegistration = Registration::query()
                ->whereKey($registration->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            // Verify amount doesn't exceed remaining_amount
            $amount = (float) $data['amount'];
            $remainingAmount = (float) $lockedRegistration->remaining_amount;

            if ($amount > $remainingAmount) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Le montant du paiement (%s) ne peut pas depasser le montant restant (%s).',
                        number_format($amount, 2, ',', ' '),
                        number_format($remainingAmount, 2, ',', ' '),
                    ),
                );
            }

            // Create the payment record
            /** @var RegistrationPayment $payment */
            $payment = $lockedRegistration->payments()->create([
                'amount' => $amount,
                'currency' => $lockedRegistration->campEdition->currency,
                'payment_method' => $data['payment_method'],
                'reference' => $data['reference'] ?? null,
                'paid_at' => $data['paid_at'],
                'validated_by' => $admin->getKey(),
                'notes' => $data['notes'] ?? null,
            ]);

            // Recalculate amounts and update registration
            $this->recalculateAmounts($lockedRegistration);

            return $payment;
        });
    }

    /**
     * Recalculate paid_amount, remaining_amount, and payment_status
     * based on confirmed payments.
     */
    public function recalculateAmounts(Registration $registration): void
    {
        DB::transaction(function () use ($registration): void {
            // Sum all confirmed payments
            $paidAmount = (float) $registration->payments()
                ->sum('amount');

            $totalAmount = (float) $registration->total_amount;
            $remainingAmount = $totalAmount - $paidAmount;

            // Determine payment status
            $paymentStatus = match (true) {
                $paidAmount <= 0 => PaymentStatus::Unpaid,
                $paidAmount < $totalAmount => PaymentStatus::Partial,
                default => PaymentStatus::Paid,
            };

            // Update registration
            $registration->update([
                'paid_amount' => $paidAmount,
                'remaining_amount' => max(0, $remainingAmount),
                'payment_status' => $paymentStatus,
            ]);
        });
    }

    /**
     * Delete a payment and recalculate amounts.
     */
    public function deletePayment(RegistrationPayment $payment): void
    {
        DB::transaction(function () use ($payment): void {
            $registration = $payment->registration;

            // Delete the payment
            $payment->delete();

            // Recalculate amounts
            $this->recalculateAmounts($registration);
        });
    }
}
