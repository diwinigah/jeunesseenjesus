<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PartnerRequestStatus;
use App\Enums\PartnerType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerRequest extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'organization_name',
        'contact_name',
        'email',
        'phone',
        'type',
        'website_url',
        'logo_path',
        'message',
        'status',
        'converted_partner_id',
        'admin_notes',
        'submitted_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'type' => PartnerType::class,
        'status' => PartnerRequestStatus::class,
        'submitted_at' => 'datetime',
    ];

    public function convertedPartner(): BelongsTo
    {
        // Une demande acceptée peut pointer vers le partenaire créé ou associé.
        return $this->belongsTo(Partner::class, 'converted_partner_id');
    }

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', PartnerRequestStatus::New->value);
    }
}
