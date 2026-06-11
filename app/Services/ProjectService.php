<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ProjectInvestorInterestStatus;
use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectService
{
    /**
     * @param array<string, mixed> $data
     */
    public function createProject(array $data): Project
    {
        $data = $this->prepareProjectData($data);
        $data['funded_amount'] = 0;

        return DB::transaction(fn (): Project => Project::query()->create($data));
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateProject(Project $project, array $data): Project
    {
        $data = $this->prepareProjectData($data, $project);

        DB::transaction(function () use ($project, $data): void {
            $project->update($data);
        });

        return $project->refresh();
    }

    public function publishProject(Project $project): void
    {
        $project->update([
            'status' => ProjectStatus::Published,
            'published_at' => $project->published_at ?? now(),
        ]);
    }

    public function archiveProject(Project $project): void
    {
        $project->update([
            'status' => ProjectStatus::Archived,
        ]);
    }

    public function updateFundedAmount(Project $project): void
    {
        $fundedAmount = (float) $project->projectInvestorInterests()
            ->whereIn('status', [ProjectInvestorInterestStatus::Pledged->value, ProjectInvestorInterestStatus::Paid->value])
            ->sum('committed_amount');

        if ($fundedAmount === 0.0) {
            $fundedAmount = (float) $project->projectInvestorInterests()
                ->whereIn('status', [ProjectInvestorInterestStatus::Pledged->value, ProjectInvestorInterestStatus::Paid->value])
                ->sum('intended_amount');
        }

        $status = $project->status;

        if ($status !== ProjectStatus::Archived) {
            if ($project->funding_goal > 0 && $fundedAmount >= (float) $project->funding_goal) {
                $status = ProjectStatus::Funded;
            } elseif ($status === ProjectStatus::Funded && $fundedAmount < (float) $project->funding_goal) {
                $status = ProjectStatus::Published;
            }
        }

        $project->funded_amount = (string) number_format((float) $fundedAmount, 2, '.', '');
        $project->status = $status;
        $project->saveQuietly();
    }

    public function getProgressPercentage(Project $project): float
    {
        $fundingGoal = (float) $project->funding_goal;

        if ($fundingGoal <= 0) {
            return 0.0;
        }

        return min(100.0, round(((float) $project->funded_amount / $fundingGoal) * 100, 2));
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function prepareProjectData(array $data, ?Project $project = null): array
    {
        $data = Arr::only($data, [
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
        ]);

        $title = (string) ($data['title'] ?? $project?->title ?? '');
        $slug = trim((string) ($data['slug'] ?? ''));

        if ($slug === '' && $title !== '') {
            $slug = Str::slug($title);
        }

        if ($slug !== '') {
            $data['slug'] = Str::slug($slug);
        }

        $data['currency'] = $data['currency'] ?? $project?->currency ?? 'XOF';
        $data['status'] = $data['status'] ?? $project?->status ?? ProjectStatus::Draft;

        $status = $data['status'] instanceof ProjectStatus ? $data['status'] : ProjectStatus::from((string) $data['status']);

        if ($status === ProjectStatus::Published && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }
}
