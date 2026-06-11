<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SectionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EditionSection extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'camp_edition_id',
        'section',
        'price',
        'description',
        'is_active',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'section' => SectionType::class,
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function campEdition(): BelongsTo
    {
        // Chaque tarif de section appartient a une edition precise du camp.
        return $this->belongsTo(CampEdition::class);
    }

    public function registrations(): HasMany
    {
        // Une section tarifaire peut regrouper plusieurs inscriptions.
        return $this->hasMany(Registration::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
