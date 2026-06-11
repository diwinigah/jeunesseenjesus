<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProjectInvestorInterestStatus;
use App\Services\ProjectService;
use Illuminate\Database\Eloquent\Attributes\Computed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectInvestorInterest extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'project_id',
        'investor_user_id',
        'intended_amount',
        'committed_amount',
        'currency',
        'status',
        'message',
        'admin_notes',
        'manual_name',
        'manual_organisation',
        'manual_email',
        'manual_phone',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'intended_amount' => 'decimal:2',
        'committed_amount' => 'decimal:2',
        'status' => ProjectInvestorInterestStatus::class,
    ];

    protected static function booted(): void
    {
        static::saved(function (ProjectInvestorInterest $interest): void {
            app(ProjectService::class)->updateFundedAmount($interest->project);
        });

        static::deleted(function (ProjectInvestorInterest $interest): void {
            app(ProjectService::class)->updateFundedAmount($interest->project);
        });
    }

    public function project(): BelongsTo
    {
        // Chaque intérêt de financement concerne un projet unique.
        return $this->belongsTo(Project::class);
    }

    public function investorUser(): BelongsTo
    {
        // Chaque intérêt de financement est porté par un investisseur authentifié.
        return $this->belongsTo(InvestorUser::class);
    }

    public function scopeByStatus(Builder $query, ProjectInvestorInterestStatus|string $status): Builder
    {
        return $query->where('status', $status instanceof ProjectInvestorInterestStatus ? $status->value : $status);
    }

    /**
     * Get the investor name (from account or manual entry).
     */
    public function getInvestorNameAttribute(): string
    {
        if ($this->investor_user_id) {
            return $this->investorUser?->name ?? '';
        }

        return $this->manual_name ?? '';
    }

    /**
     * Get the investor email (from account or manual entry).
     */
    public function getInvestorEmailAttribute(): string
    {
        if ($this->investor_user_id) {
            return $this->investorUser?->email ?? '';
        }

        return $this->manual_email ?? '';
    }

    /**
     * Get the investor phone (from account or manual entry).
     */
    public function getInvestorPhoneAttribute(): string
    {
        if ($this->investor_user_id) {
            return $this->investorUser?->phone ?? '';
        }

        return $this->manual_phone ?? '';
    }

    /**
     * Determine if investor has an account.
     */
    #[Computed]
    public function hasAccount(): bool
    {
        return (bool) $this->investor_user_id;
    }
}
