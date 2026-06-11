<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PartnerStatus;
use App\Enums\PartnerType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (self $partner): void {
            // Supprimer toutes les demandes de partenariat converties
            $partner->partnerRequests()->delete();
        });
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'logo_path',
        'website_url',
        'email',
        'phone',
        'is_public',
        'display_order',
        'status',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'phone',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'type' => PartnerType::class,
        'is_public' => 'boolean',
        'display_order' => 'integer',
        'status' => PartnerStatus::class,
    ];

    public function partnerRequests(): HasMany
    {
        // Un partenaire validé peut provenir d'une ou plusieurs demandes publiques converties.
        return $this->hasMany(PartnerRequest::class, 'converted_partner_id');
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', PartnerStatus::Active->value);
    }
}
