<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use App\Enums\PaymentStatus;
use App\Enums\RegistrationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registration extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'camp_edition_id',
        'edition_section_id',
        'registration_number',
        'first_name',
        'last_name',
        'gender',
        'phone',
        'whatsapp_phone',
        'city',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'payment_status',
        'registration_status',
        'notes',
        'admin_notes',
        'submitted_at',
        'confirmed_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'gender' => Gender::class,
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'payment_status' => PaymentStatus::class,
        'registration_status' => RegistrationStatus::class,
        'submitted_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function campEdition(): BelongsTo
    {
        // Chaque inscription appartient obligatoirement a une edition du camp.
        return $this->belongsTo(CampEdition::class);
    }

    public function editionSection(): BelongsTo
    {
        // Chaque inscription utilise un tarif de section defini pour son edition.
        return $this->belongsTo(EditionSection::class);
    }

    public function payments(): HasMany
    {
        // Une inscription peut recevoir plusieurs paiements manuels successifs.
        return $this->hasMany(RegistrationPayment::class);
    }

    public function scopeByEdition(Builder $query, int|CampEdition $edition): Builder
    {
        $editionId = $edition instanceof CampEdition ? $edition->getKey() : $edition;

        return $query->where('camp_edition_id', $editionId);
    }

    public function scopeByPaymentStatus(Builder $query, PaymentStatus|string $status): Builder
    {
        return $query->where('payment_status', $status instanceof PaymentStatus ? $status->value : $status);
    }
}
