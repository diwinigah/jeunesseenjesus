<?php

declare(strict_types=1);

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvestmentRequest;
use App\Models\Project;
use App\Services\InvestorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvestorController extends Controller
{
    public function __construct(private InvestorService $investorService)
    {
    }

    public function showInvestForm(Project $project): View|RedirectResponse
    {
        if (! Auth::guard('investor')->check()) {
            session()->put('url.intended', route('projects.invest.form', ['project' => $project->slug]));

            return redirect()->guest(route('investor.login'));
        }

        return view('investor.invest', [
            'project' => $project,
        ]);
    }

    public function invest(Project $project, StoreInvestmentRequest $request): RedirectResponse
    {
        $investor = Auth::guard('investor')->user();

        $this->investorService->createInvestment(
            $investor,
            $project,
            $request->validated()
        );

        return redirect()
            ->route('investor.dashboard')
            ->with('success', 'Votre proposition d\'investissement a été enregistrée avec succès.');
    }
}
