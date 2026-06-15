<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CampEditionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampEdition extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'year',
        'description',
        'cover_image_path',
        'registration_open_at',
        'registration_close_at',
        'camp_start_date',
        'camp_end_date',
        'currency',
        'status',
        'is_active',
        'show_days_presence', 'show_children_count', 'show_bus_departure',
        'show_participant_type',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'year' => 'integer',
        'registration_open_at' => 'datetime',
        'registration_close_at' => 'datetime',
        'camp_start_date' => 'date',
        'camp_end_date' => 'date',
        'status' => CampEditionStatus::class,
        'is_active' => 'boolean',
        'show_days_presence' => 'boolean',
        'show_children_count' => 'boolean',
        'show_bus_departure' => 'boolean',
        'show_participant_type' => 'boolean',
    ];

    public function registrations(): HasMany
    {
        // Une edition de camp regroupe plusieurs inscriptions publiques.
        return $this->hasMany(Registration::class);
    }

    public function editionSections(): HasMany
    {
        // Une edition definit ses propres sections officielles et tarifs.
        return $this->hasMany(EditionSection::class);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', CampEditionStatus::Open->value);
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', CampEditionStatus::Archived->value);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
