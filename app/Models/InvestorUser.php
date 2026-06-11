<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContactMethod;
use App\Enums\InvestorStatus;
use App\Enums\InvestorType;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class InvestorUser extends Authenticatable
{
    use Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'name',
        'organization_name',
        'email',
        'password',
        'phone',
        'city',
        'country',
        'preferred_contact_method',
        'status',
        'notes',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'phone',
        'password',
        'remember_token',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'type' => InvestorType::class,
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'preferred_contact_method' => ContactMethod::class,
        'status' => InvestorStatus::class,
    ];

    public function projectInvestorInterests(): HasMany
    {
        // Un investisseur peut exprimer des intérêts sur plusieurs projets.
        return $this->hasMany(ProjectInvestorInterest::class);
    }

    public function projects(): BelongsToMany
    {
        // Un investisseur peut financer plusieurs projets via la table pivot enrichie.
        return $this->belongsToMany(Project::class, 'project_investor_interests')
            ->withPivot(['id', 'intended_amount', 'committed_amount', 'currency', 'status', 'message', 'admin_notes'])
            ->withTimestamps();
    }
}
