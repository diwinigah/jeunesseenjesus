<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Registration;
use App\Models\CampEdition;

class PublicRegistrationListController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $activeEdition = CampEdition::where('is_active', true)->first();

        if (!$activeEdition) {
            return view('public.inscriptions', [
                'registrations' => collect(),
                'edition'       => null,
                'stats'         => null,
            ]);
        }

        $registrations = Registration::where('camp_edition_id', $activeEdition->id)
            ->with('editionSection')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $stats = [
            'eleve'    => $registrations->where('participant_type', 'eleve')->count(),
            'etudiant' => $registrations->where('participant_type', 'etudiant')->count(),
            'adulte'   => $registrations->where('participant_type', 'adulte')->count(),
            'total'    => $registrations->count(),
        ];

        return view('public.inscriptions', [
            'registrations' => $registrations,
            'edition'       => $activeEdition,
            'stats'         => $stats,
        ]);
    }
}
