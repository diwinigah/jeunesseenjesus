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
        // Activités et inscription
        'activities_link_label',
        'activities_link_url',
        'registration_mode',
        'external_registration_label',
        'external_registration_url',
        'registration_page_title',
        // Sponsoring
        'sponsoring_theme', 'sponsoring_intro', 'sponsoring_verse', 'show_sponsoring_page',
        'sponsoring_salutation',
        'budget_total', 'budget_collected', 'participants_target', 'participants_sponsored',
        'budget_entries', 'budget_expenses',
        'bourse_pleine_amount', 'bourse_adulte_amount', 'bourse_etudiant_amount', 'bourse_lycee_amount', 'bourse_enfant_amount',
        'bourse_pleine_label', 'bourse_pleine_desc', 'bourse_partielle_label', 'bourse_partielle_desc',
        'categorie_adulte_label', 'categorie_etudiant_label', 'categorie_lycee_label', 'categorie_enfant_label',
        'payment_flooz', 'payment_mixx', 'payment_iban', 'payment_paypal', 'payment_account_name', 'payment_account_number',
        'sponsoring_contact_phone', 'sponsoring_contact_email', 'nature_contributions',
        // Titres personnalisables des sections
        'title_bourses', 'title_frais', 'title_nature', 'title_paiement',
        // Liens externes
        'external_links',
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
        'registration_mode' => 'string',
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
        'sponsoring_salutation' => 'string',
        'nature_contributions' => 'array',
        'external_links' => 'array',
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
