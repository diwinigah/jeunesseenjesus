@extends('layouts.app')

@section('title', 'Mot de passe oublié - Investisseur')

@push('styles')
<style>
    .inv-fp-container { width: min(100%, 500px); margin: 40px auto; }
    .inv-fp-box { background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; padding: 24px; }
    .inv-fp-h1 { margin: 0 0 12px; font-size: 1.8rem; color: #333; }
    .inv-fp-subtitle { color: #6b7280; margin-bottom: 24px; font-size: 0.95rem; }
    .inv-fp-group { margin-bottom: 16px; }
    .inv-fp-label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.9rem; color: #333; }
    .inv-fp-input { width: 100%; padding: 10px; border: 1px solid #dfe3ea; border-radius: 4px; font-size: 0.95rem; font-family: Arial, sans-serif; color: #333; }
    .inv-fp-input:focus { outline: none; border-color: #E8490F; box-shadow: 0 0 0 3px rgba(232, 73, 15, 0.1); }
    .inv-fp-error { color: #dc2626; font-size: 0.85rem; margin-top: 4px; }
    .inv-fp-success { background: #d1fae5; color: #065f46; padding: 12px; border-radius: 4px; margin-bottom: 16px; font-size: 0.9rem; }
    .inv-fp-group.error .inv-fp-input { border-color: #dc2626; }
    .inv-fp-btn { display: inline-flex; width: 100%; align-items: center; justify-content: center; min-height: 44px; background: #E8490F; color: #fff; border: none; border-radius: 6px; font-size: 0.95rem; font-weight: 700; cursor: pointer; margin-top: 12px; }
    .inv-fp-btn:hover { background: #C73D0A; }
    .inv-fp-link { text-align: center; margin-top: 16px; }
    .inv-fp-link a { color: #E8490F; text-decoration: none; font-weight: 600; }
    @media (max-width: 600px) { .inv-fp-container { margin: 20px auto; } .inv-fp-box { padding: 18px; } }
</style>
@endpush

@section('content')
<div class="inv-fp-container">
    <div class="inv-fp-box">
        <h1 class="inv-fp-h1">Mot de passe oublié</h1>
        <p class="inv-fp-subtitle">Entrez votre adresse email pour recevoir un lien de réinitialisation.</p>

        @if ($errors->any())
            <div class="inv-fp-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="inv-fp-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('investor.password.email') }}">
            @csrf

            <div class="inv-fp-group @error('email') error @enderror">
                <label for="email" class="inv-fp-label">Email *</label>
                <input type="email" name="email" id="email" class="inv-fp-input" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="inv-fp-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="inv-fp-btn">Envoyer le lien</button>
        </form>

        <div class="inv-fp-link">
            <a href="{{ route('investor.login') }}">Retour à la connexion</a>
        </div>
    </div>
</div>
@endsection
</body>
</html>
