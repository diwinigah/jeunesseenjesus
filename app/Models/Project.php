<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'funding_goal',
        'funded_amount',
        'currency',
        'status',
        'featured_image_path',
        'is_featured',
        'published_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'funding_goal' => 'decimal:2',
        'funded_amount' => 'decimal:2',
        'status' => ProjectStatus::class,
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function projectInvestorInterests(): HasMany
    {
        // Un projet peut recevoir plusieurs intérêts ou engagements d'investisseurs.
        return $this->hasMany(ProjectInvestorInterest::class);
    }

    public function investors(): BelongsToMany
    {
        // Un projet peut être financé par plusieurs investisseurs via la table pivot enrichie.
        return $this->belongsToMany(InvestorUser::class, 'project_investor_interests')
            ->withPivot(['id', 'intended_amount', 'committed_amount', 'currency', 'status', 'message', 'admin_notes'])
            ->withTimestamps();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (Project $project) {
            $project->projectInvestorInterests()->delete();
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ProjectStatus::Published->value);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
