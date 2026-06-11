<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\InvestorUser;
use App\Models\Project;
use App\Models\ProjectInvestorInterest;
use App\Models\User;
use App\Notifications\NewInvestmentNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class InvestorService
{
    /**
     * @param array<string, mixed> $data
     */
    public function registerInvestor(array $data): InvestorUser
    {
        $data = [
            'type' => $data['type'] ?? 'individual',
            'name' => $data['name'],
            'organization_name' => $data['organization_name'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
            'preferred_contact_method' => 'email',
            'status' => 'active',
        ];

        return DB::transaction(fn (): InvestorUser => InvestorUser::query()->create($data));
    }

    /**
     * Create a new investment interest for an investor on a project.
     *
     * @param array<string, mixed> $data
     */
    public function createInvestment(InvestorUser $investor, Project $project, array $data): ProjectInvestorInterest
    {
        return DB::transaction(function () use ($investor, $project, $data): ProjectInvestorInterest {
            $interest = ProjectInvestorInterest::query()->create([
                'project_id' => $project->id,
                'investor_user_id' => $investor->id,
                'intended_amount' => (float) $data['intended_amount'],
                'currency' => 'XOF',
                'status' => 'new',
                'message' => $data['message'] ?? null,
            ]);

            // Notify admins of new investment
            Notification::send(
                User::query()->get(),
                new NewInvestmentNotification($investor, $project, (float) $data['intended_amount']),
            );

            return $interest;
        });
    }
}
