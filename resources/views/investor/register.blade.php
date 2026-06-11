@extends('layouts.app')

@section('title', "S'inscrire - Investisseur")

@push('styles')
<style>
    .inv-reg-container { width: min(100%, 500px); margin: 40px auto; }
    .inv-reg-form-box { background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; padding: 24px; }
    .inv-reg-h1 { margin: 0 0 24px; font-size: 1.8rem; color: #333; }
    .inv-reg-group { margin-bottom: 16px; }
    .inv-reg-label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.9rem; color: #333; }
    .inv-reg-input, .inv-reg-textarea { width: 100%; padding: 10px; border: 1px solid #dfe3ea; border-radius: 4px; font-size: 0.95rem; font-family: Arial, sans-serif; color: #333; }
    .inv-reg-input:focus, .inv-reg-textarea:focus { outline: none; border-color: #E8490F; box-shadow: 0 0 0 3px rgba(232, 73, 15, 0.1); }
    .inv-reg-error-msg { color: #dc2626; font-size: 0.85rem; margin-top: 4px; }
    .inv-reg-group.error .inv-reg-input { border-color: #dc2626; }
    .inv-reg-btn { display: inline-flex; width: 100%; align-items: center; justify-content: center; min-height: 44px; background: #E8490F; color: #fff; border: none; border-radius: 6px; font-size: 0.95rem; font-weight: 700; cursor: pointer; margin-top: 12px; }
    .inv-reg-btn:hover { background: #C73D0A; }
    .inv-reg-btn:disabled { background: #ccc; cursor: not-allowed; }
    .inv-reg-link { text-align: center; margin-top: 16px; }
    .inv-reg-link a { color: #E8490F; text-decoration: none; font-weight: 600; }
    .inv-reg-link a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="inv-reg-container">
    <div class="inv-reg-form-box">
        <h1 class="inv-reg-h1">S'inscrire</h1>

        <form method="POST" action="{{ route('investor.register') }}">
            @csrf

            <div class="inv-reg-group @if ($errors->has('name') && session('_old_input')) error @endif">
                <label for="name" class="inv-reg-label">Nom complet *</label>
                <input type="text" name="name" id="name" class="inv-reg-input" value="{{ old('name') }}" required>
                @if ($errors->has('name') && session('_old_input'))
                    <div class="inv-reg-error-msg">{{ $errors->first('name') }}</div>
                @endif
            </div>

            <div class="inv-reg-group @if ($errors->has('organization_name') && session('_old_input')) error @endif">
                <label for="organization_name" class="inv-reg-label">Organisation</label>
                <input type="text" name="organization_name" id="organization_name" class="inv-reg-input" value="{{ old('organization_name') }}">
                @if ($errors->has('organization_name') && session('_old_input'))
                    <div class="inv-reg-error-msg">{{ $errors->first('organization_name') }}</div>
                @endif
            </div>

            <div class="inv-reg-group @if ($errors->has('email') && session('_old_input')) error @endif">
                <label for="email" class="inv-reg-label">Email *</label>
                <input type="email" name="email" id="email" class="inv-reg-input" value="{{ old('email') }}" required>
                @if ($errors->has('email') && session('_old_input'))
                    <div class="inv-reg-error-msg">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="inv-reg-group @if ($errors->has('phone') && session('_old_input')) error @endif">
                <label for="phone" class="inv-reg-label">Téléphone *</label>
                <input type="tel" name="phone" id="phone" class="inv-reg-input" value="{{ old('phone') }}" placeholder="+226 XX XX XX XX" required>
                @if ($errors->has('phone') && session('_old_input'))
                    <div class="inv-reg-error-msg">{{ $errors->first('phone') }}</div>
                @endif
            </div>

            <div class="inv-reg-group @if ($errors->has('password') && session('_old_input')) error @endif">
                <label for="password" class="inv-reg-label">Mot de passe *</label>
                <input type="password" name="password" id="password" class="inv-reg-input" required>
                @if ($errors->has('password') && session('_old_input'))
                    <div class="inv-reg-error-msg">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <div class="inv-reg-group @if ($errors->has('password_confirmation') && session('_old_input')) error @endif">
                <label for="password_confirmation" class="inv-reg-label">Confirmer le mot de passe *</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="inv-reg-input" required>
                @if ($errors->has('password_confirmation') && session('_old_input'))
                    <div class="inv-reg-error-msg">{{ $errors->first('password_confirmation') }}</div>
                @endif
            </div>

            <button
                type="submit"
                id="submit-btn"
                class="inv-reg-btn"
                onclick="this.disabled=true;
                         this.innerText='Envoi en cours...';
                         this.form.submit();">
                S'inscrire
            </button>
        </form>

        <div class="inv-reg-link">
            Vous avez déjà un compte ? <a href="{{ route('investor.login') }}">Se connecter</a>
        </div>
    </div>
</div>
@endsection
