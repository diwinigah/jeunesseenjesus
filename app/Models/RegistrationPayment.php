<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationPayment extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'registration_id',
        'amount',
        'currency',
        'payment_method',
        'reference',
        'paid_at',
        'validated_by',
        'notes',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_method' => PaymentMethod::class,
        'paid_at' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        // Chaque paiement est rattaché à une inscription précise.
        return $this->belongsTo(Registration::class);
    }

    public function validator(): BelongsTo
    {
        // Le validateur identifie l'administrateur ayant confirmé le paiement.
        return $this->belongsTo(User::class, 'validated_by');
    }
}
