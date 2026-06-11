<?php

declare(strict_types=1);

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvestorLoginRequest;
use App\Http\Requests\InvestorRegisterRequest;
use App\Models\ProjectInvestorInterest;
use App\Services\InvestorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvestorAuthController extends Controller
{
    public function __construct(private InvestorService $investorService)
    {
    }

    public function showRegister(): View
    {
        return view('investor.register');
    }

    public function register(InvestorRegisterRequest $request): RedirectResponse
    {
        $investor = $this->investorService->registerInvestor($request->validated());

        Auth::guard('investor')->login($investor);

        return redirect()->intended('/projets');
    }

    public function showLogin(): View
    {
        return view('investor.login');
    }

    public function login(InvestorLoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::guard('investor')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/projets');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('investor')->logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect('/projets');
    }

    public function dashboard(): View
    {
        $investor = Auth::guard('investor')->user();

        $investments = ProjectInvestorInterest::query()
            ->where('investor_user_id', $investor->id)
            ->with('project')
            ->latest('created_at')
            ->get();

        return view('investor.dashboard', [
            'investments' => $investments,
        ]);
    }
}
