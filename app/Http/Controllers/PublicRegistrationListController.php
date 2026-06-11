<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CampEditionService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;

class PublicRegistrationListController extends Controller
{
    public function __construct(
        private readonly CampEditionService $campEditionService,
    ) {}

    public function index(): View|RedirectResponse
    {
        $edition = $this->campEditionService->getCurrentActiveEdition();

        if ($edition === null) {
            return redirect()
                ->route('registration.show')
                ->with('status', 'Aucune edition active actuellement');
        }

        /** @var LengthAwarePaginator<Registration> $registrations */
        $registrations = Registration::query()
            ->select([
                'id',
                'registration_number',
                'first_name',
                'last_name',
                'city',
                'payment_status',
                'registration_status',
                'edition_section_id',
                'submitted_at',
            ])
            ->where('camp_edition_id', $edition->getKey())
            ->with(['editionSection'])
            ->orderBy('submitted_at', 'desc')
            ->paginate(20);

        return view('public.inscriptions', [
            'edition' => $edition,
            'registrations' => $registrations,
        ]);
    }
}
