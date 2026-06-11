<?php

declare(strict_types=1);

namespace App\Http\Controllers\Investor;

use App\Models\InvestorUser;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class InvestorPasswordResetController
{
    public function showForgotForm(): View
    {
        return view('investor.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email|exists:investor_users,email']);

        $status = Password::broker('investors')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Lien de réinitialisation envoyé à votre adresse email.')
            : back()->withErrors(['email' => trans($status)]);
    }

    public function showResetForm(string $token): View
    {
        return view('investor.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:investor_users,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string',
        ]);

        $status = Password::broker('investors')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (InvestorUser $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('investor.login')->with('status', 'Mot de passe réinitialisé avec succès.')
            : back()->withErrors(['email' => trans($status)]);
    }
}
