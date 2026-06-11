@extends('layouts.app')

@section('title', 'Réinitialiser le mot de passe - Investisseur')

@push('styles')
<style>
    .inv-rp-container { width: min(100%, 500px); margin: 40px auto; }
    .inv-rp-box { background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; padding: 24px; }
    .inv-rp-h1 { margin: 0 0 24px; font-size: 1.8rem; color: #333; }
    .inv-rp-group { margin-bottom: 16px; }
    .inv-rp-label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.9rem; color: #333; }
    .inv-rp-input { width: 100%; padding: 10px; border: 1px solid #dfe3ea; border-radius: 4px; font-size: 0.95rem; font-family: Arial, sans-serif; color: #333; }
    .inv-rp-input:focus { outline: none; border-color: #E8490F; box-shadow: 0 0 0 3px rgba(232, 73, 15, 0.1); }
    .inv-rp-error { color: #dc2626; font-size: 0.85rem; margin-top: 4px; }
    .inv-rp-group.error .inv-rp-input { border-color: #dc2626; }
    .inv-rp-btn { display: inline-flex; width: 100%; align-items: center; justify-content: center; min-height: 44px; background: #E8490F; color: #fff; border: none; border-radius: 6px; font-size: 0.95rem; font-weight: 700; cursor: pointer; margin-top: 12px; }
    .inv-rp-btn:hover { background: #C73D0A; }
    .inv-rp-link { text-align: center; margin-top: 16px; }
    .inv-rp-link a { color: #E8490F; text-decoration: none; font-weight: 600; }
    @media (max-width: 600px) { .inv-rp-container { margin: 20px auto; } .inv-rp-box { padding: 18px; } }
</style>
@endpush

@section('content')
<div class="inv-rp-container">
    <div class="inv-rp-box">
        <h1 class="inv-rp-h1">Réinitialiser le mot de passe</h1>

        @if ($errors->any())
            <div class="inv-rp-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('investor.password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="inv-rp-group @error('email') error @enderror">
                <label for="email" class="inv-rp-label">Email *</label>
                <input type="email" name="email" id="email" class="inv-rp-input" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="inv-rp-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="inv-rp-group @error('password') error @enderror">
                <label for="password" class="inv-rp-label">Nouveau mot de passe *</label>
                <input type="password" name="password" id="password" class="inv-rp-input" required>
                @error('password')
                    <div class="inv-rp-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="inv-rp-group @error('password_confirmation') error @enderror">
                <label for="password_confirmation" class="inv-rp-label">Confirmer le mot de passe *</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="inv-rp-input" required>
                @error('password_confirmation')
                    <div class="inv-rp-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="inv-rp-btn">Réinitialiser le mot de passe</button>
        </form>

        <div class="inv-rp-link">
            <a href="{{ route('investor.login') }}">Retour à la connexion</a>
        </div>
    </div>
</div>
@endsection
</body>
</html>
