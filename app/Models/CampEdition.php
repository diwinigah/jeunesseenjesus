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
        // Sponsoring
        'sponsoring_theme', 'sponsoring_intro', 'sponsoring_verse', 'show_sponsoring_page',
        'budget_total', 'budget_collected', 'participants_target', 'participants_sponsored',
        'budget_entries', 'budget_expenses',
        'participants_adultes', 'participants_etudiants', 'participants_lycee', 'participants_enfants', 'participants_geo',
        'bourse_pleine_amount', 'bourse_adulte_amount', 'bourse_etudiant_amount', 'bourse_lycee_amount', 'bourse_enfant_amount',
        'payment_flooz', 'payment_mixx', 'payment_iban', 'payment_paypal', 'payment_account_name', 'payment_account_number',
        'sponsoring_contact_phone', 'sponsoring_contact_email', 'nature_contributions',
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
        // Sponsoring
        'show_sponsoring_page' => 'boolean',
        'budget_total' => 'integer',
        'budget_collected' => 'integer',
        'participants_target' => 'integer',
        'participants_sponsored' => 'integer',
        'budget_entries' => 'array',
        'budget_expenses' => 'array',
        'participants_adultes' => 'integer',
        'participants_etudiants' => 'integer',
        'participants_lycee' => 'integer',
        'participants_enfants' => 'integer',
        'participants_geo' => 'array',
        'bourse_pleine_amount' => 'integer',
        'bourse_adulte_amount' => 'integer',
        'bourse_etudiant_amount' => 'integer',
        'bourse_lycee_amount' => 'integer',
        'bourse_enfant_amount' => 'integer',
        'nature_contributions' => 'array',
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
