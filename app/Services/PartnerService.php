<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PartnerRequestStatus;
use App\Enums\PartnerStatus;
use App\Models\Partner;
use App\Models\PartnerRequest;
use App\Notifications\NewPartnerRequestNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class PartnerService
{
    /**
     * Crée une nouvelle demande de partenariat.
     *
     * @param  array<string, mixed>  $data
     */
    public function createPartnerRequest(array $data): PartnerRequest
    {
        return DB::transaction(function () use ($data): PartnerRequest {
            // Créer la demande
            $request = PartnerRequest::create([
                'organization_name' => $data['organization_name'],
                'contact_name' => $data['contact_name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'],
                'type' => $data['type'] ?? null,
                'website_url' => $data['website_url'] ?? null,
                'message' => $data['message'] ?? null,
                'status' => PartnerRequestStatus::New->value,
                'submitted_at' => now(),
            ]);

            // Envoyer la notification aux admins
            $admins = User::all();
            foreach ($admins as $admin) {
                $admin->notify(new NewPartnerRequestNotification($request));
            }

            return $request;
        });
    }

    /**
     * Approuve une demande de partenariat.
     */
    public function approveRequest(PartnerRequest $request): PartnerRequest
    {
        return DB::transaction(function () use ($request): PartnerRequest {
            $request->update([
                'status' => PartnerRequestStatus::Accepted->value,
            ]);

            return $request->refresh();
        });
    }

    /**
     * Rejette une demande de partenariat.
     */
    public function rejectRequest(PartnerRequest $request): PartnerRequest
    {
        return DB::transaction(function () use ($request): PartnerRequest {
            $request->update([
                'status' => PartnerRequestStatus::Rejected->value,
            ]);

            return $request->refresh();
        });
    }

    /**
     * Convertit une demande acceptée en partenaire.
     */
    public function convertToPartner(PartnerRequest $request): Partner
    {
        return DB::transaction(function () use ($request): Partner {
            // Créer le partenaire
            $partner = Partner::create([
                'name' => $request->organization_name,
                'slug' => Str::slug($request->organization_name),
                'type' => $request->type,
                'description' => $request->message ?? null,
                'logo_path' => $request->logo_path,
                'email' => $request->email ?? null,
                'phone' => $request->phone,
                'website_url' => $request->website_url ?? null,
                'is_public' => true,
                'display_order' => Partner::max('display_order') + 1,
                'status' => PartnerStatus::Active->value,
            ]);

            // Mettre à jour la demande
            $request->update([
                'converted_partner_id' => $partner->id,
            ]);

            return $partner;
        });
    }
}
