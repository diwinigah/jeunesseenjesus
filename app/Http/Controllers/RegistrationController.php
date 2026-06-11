<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Services\RegistrationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RegistrationController extends Controller
{
    public function show(RegistrationService $registrationService): View
    {
        if (! $registrationService->isRegistrationOpen()) {
            return view('registration.closed');
        }

        return view('registration.show', [
            'edition' => $registrationService->getOpenEdition(),
            'sections' => $registrationService->getOpenEditionSections(),
        ]);
    }

    public function store(StoreRegistrationRequest $request, RegistrationService $registrationService): RedirectResponse
    {
        $edition = $registrationService->getOpenEdition();

        if ($edition === null) {
            return redirect()
                ->route('registration.show')
                ->with('status', 'Inscriptions fermees');
        }

        $registration = $registrationService->createRegistration($request->validated(), $edition);

        return redirect()
            ->route('registration.confirmation')
            ->with('registration_number', $registration->registration_number)
            ->with('first_name', $registration->first_name);
    }

    public function confirmation(): View|RedirectResponse
    {
        $registrationNumber = session('registration_number');
        $firstName = session('first_name');

        if ($registrationNumber === null || $firstName === null) {
            return redirect()->route('registration.show');
        }

        return view('registration.success', [
            'registration' => $registrationNumber,
            'first_name' => $firstName,
        ]);
    }
}
