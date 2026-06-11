@extends('layouts.app')

@section('title', 'Se connecter - Investisseur')

@push('styles')
<style>
    .inv-login-container { width: min(100%, 500px); margin: 40px auto; }
    .inv-login-box { background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; padding: 24px; }
    .inv-login-h1 { margin: 0 0 24px; font-size: 1.8rem; color: #333; }
    .inv-login-group { margin-bottom: 16px; }
    .inv-login-label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.9rem; color: #333; }
    .inv-login-input { width: 100%; padding: 10px; border: 1px solid #dfe3ea; border-radius: 4px; font-size: 0.95rem; font-family: Arial, sans-serif; color: #333; }
    .inv-login-input:focus { outline: none; border-color: #E8490F; box-shadow: 0 0 0 3px rgba(232, 73, 15, 0.1); }
    .inv-login-error { color: #dc2626; font-size: 0.85rem; margin-top: 4px; }
    .inv-login-group.error .inv-login-input { border-color: #dc2626; }
    .inv-login-btn { display: inline-flex; width: 100%; align-items: center; justify-content: center; min-height: 44px; background: #E8490F; color: #fff; border: none; border-radius: 6px; font-size: 0.95rem; font-weight: 700; cursor: pointer; margin-top: 12px; }
    .inv-login-btn:hover { background: #C73D0A; }
    .inv-login-links { text-align: center; margin-top: 16px; }
    .inv-login-links div { margin-bottom: 8px; }
    .inv-login-links a { color: #E8490F; text-decoration: none; font-weight: 600; }
    .inv-login-links a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="inv-login-container">
    <div class="inv-login-box">
        <h1 class="inv-login-h1">Se connecter</h1>

        <form method="POST" action="{{ route('investor.login') }}">
            @csrf

            <div class="inv-login-group @error('email') error @enderror">
                <label for="email" class="inv-login-label">Email *</label>
                <input type="email" name="email" id="email" class="inv-login-input" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="inv-login-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="inv-login-group @error('password') error @enderror">
                <label for="password" class="inv-login-label">Mot de passe *</label>
                <input type="password" name="password" id="password" class="inv-login-input" required>
                @error('password')
                    <div class="inv-login-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="inv-login-btn">Se connecter</button>
        </form>

        <div class="inv-login-links">
            <div>Pas encore inscrit ? <a href="{{ route('investor.register') }}">S'inscrire</a></div>
            <div><a href="{{ route('investor.password.request') }}">Mot de passe oublié ?</a></div>
        </div>
    </div>
</div>
@endsection
