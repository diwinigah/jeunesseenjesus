<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CampEdition;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class SponsoringController extends BaseController
{
    public function index(Request $request)
    {
        // Charger l'édition active affichable pour le sponsoring en une seule requête.
        // Respecte la colonne `show_sponsoring_page` qui est false par défaut.
        $edition = CampEdition::query()
            ->where('show_sponsoring_page', true)
            ->where('is_active', true)
            ->withCount(['registrations'])
            ->first();

        return view('sponsoring.index', compact('edition'));
    }
}
