<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePartnerRequestRequest;
use App\Models\Partner;
use App\Services\PartnerService;

class PartnerController extends Controller
{
    /**
     * Affiche la liste des partenaires publics.
     */
    public function index()
    {
        $partners = Partner::query()
            ->active()
            ->public()
            ->select(['id', 'name', 'slug', 'type', 'logo_path', 'description', 'website_url', 'display_order'])
            ->orderBy('display_order', 'asc')
            ->get();

        return view('partners.index', compact('partners'));
    }

    /**
     * Affiche le formulaire de demande de partenariat.
     */
    public function showRequestForm()
    {
        return view('partners.request');
    }

    /**
     * Crée une nouvelle demande de partenariat.
     */
    public function storeRequest(StorePartnerRequestRequest $request)
    {
        $service = new PartnerService();
        $service->createPartnerRequest($request->validated());

        return redirect()->route('partners.confirmation');
    }

    /**
     * Affiche la page de confirmation.
     */
    public function confirmation()
    {
        return view('partners.confirmation');
    }
}
