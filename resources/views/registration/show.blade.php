@extends('layouts.app')

@section('title', 'Inscription au camp')

@push('styles')
<style>
    .reg-header { margin-bottom: 22px; }
    .reg-header h1 { margin: 0 0 8px; font-size: clamp(1.7rem, 5vw, 2.4rem); }
    .reg-header p { line-height: 1.55; }
    .reg-form { background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; padding: 18px; }
    .reg-grid { display: grid; grid-template-columns: 1fr; gap: 16px; }
    .reg-label { display: block; font-weight: 700; margin-bottom: 6px; }
    .reg-input { width: 100%; border: 1px solid #cbd3df; border-radius: 6px; padding: 11px 12px; font-size: 1rem; }
    .reg-input:focus { outline: none; border-color: #E8490F; }
    .reg-textarea { min-height: 110px; resize: vertical; }
    .reg-error { margin-top: 6px; color: #b42318; font-size: .92rem; }
    .reg-alert { border: 1px solid #f2c8c4; background: #fff5f4; color: #8a1f15; border-radius: 6px; padding: 12px; margin-bottom: 16px; }
    .reg-button { width: 100%; border: 0; border-radius: 6px; background: #E8490F; color: #fff; padding: 13px 16px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: background 0.2s; }
    .reg-button:hover { background: #C73D0A; }
    .reg-hint { color: #667085; font-size: .94rem; margin-top: 4px; }
    .reg-form-note { font-size: 0.78rem; color: #888; margin-top: 4px; line-height: 1.4; }
    .reg-form-note strong { color: #c0392b; }
    @media (min-width: 680px) { .reg-grid.two { grid-template-columns: repeat(2, 1fr); } }
</style>
@endpush

@section('content')
<div class="reg-header">
    <h1>Inscription Evenement</h1>
    <p>{{ $edition->name }} - inscriptions ouvertes jusqu'au {{ $edition->registration_close_at->format('d/m/Y H:i') }}.</p>
</div>

@if ($errors->any() && session('_old_input'))
    <div class="reg-alert">Merci de corriger les champs indiques.</div>
@endif

<form method="POST" action="{{ route('registration.store') }}" class="reg-form">
    @csrf

    <div class="reg-grid two">
        <div>
            <label for="first_name" class="reg-label">Prenom</label>
            <input id="first_name" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" class="reg-input">
            @if ($errors->has('first_name') && session('_old_input')) <div class="reg-error">{{ $errors->first('first_name') }}</div> @endif
        </div>

        <div>
            <label for="last_name" class="reg-label">Nom</label>
            <input id="last_name" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name" class="reg-input">
            @if ($errors->has('last_name') && session('_old_input')) <div class="reg-error">{{ $errors->first('last_name') }}</div> @endif
        </div>
    </div>

    <div class="reg-grid two" style="margin-top:16px">
        <div>
            <label for="gender" class="reg-label">Genre</label>
            <select id="gender" name="gender" required class="reg-input">
                <option value="">Choisir</option>
                <option value="male" @selected(old('gender') === 'male')>Homme</option>
                <option value="female" @selected(old('gender') === 'female')>Femme</option>
                <option value="other" @selected(old('gender') === 'other')>Autre</option>
            </select>
            @if ($errors->has('gender') && session('_old_input')) <div class="reg-error">{{ $errors->first('gender') }}</div> @endif
        </div>

        <div>
            <label for="edition_section_id" class="reg-label">Section</label>
            <select id="edition_section_id" name="edition_section_id" required class="reg-input">
                <option value="">Choisir une section</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}" @selected((string) old('edition_section_id') === (string) $section->id)>
                        {{ $section->section->label() }} - {{ number_format((float) $section->price, 0, ',', ' ') }} {{ $edition->currency }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('edition_section_id') && session('_old_input')) <div class="reg-error">{{ $errors->first('edition_section_id') }}</div> @endif
        </div>
    </div>

    <div class="reg-grid two" style="margin-top:16px">
        <div>
            <label for="phone" class="reg-label">Telephone</label>
            <input id="phone" name="phone" type="tel" inputmode="tel" pattern="[\+0-9\s\-\(\)]{7,20}" value="{{ old('phone') }}" required autocomplete="tel" class="reg-input">
            @if ($errors->has('phone') && session('_old_input')) <div class="reg-error">{{ $errors->first('phone') }}</div> @endif
            <p class="reg-form-note">
                📞 Indicatif international accepté (ex: +228, +33).<br>
                <strong>NB :</strong> Numéro valide exigé pour
                la finalisation du paiement et de l'inscription.
            </p>
        </div>

        <div>
            <label for="whatsapp_phone" class="reg-label">WhatsApp</label>
            <input id="whatsapp_phone" name="whatsapp_phone" type="tel" inputmode="tel" pattern="[\+0-9\s\-\(\)]{7,20}" value="{{ old('whatsapp_phone') }}" autocomplete="tel" class="reg-input">
            <div class="reg-hint">Facultatif.</div>
            @if ($errors->has('whatsapp_phone') && session('_old_input')) <div class="reg-error">{{ $errors->first('whatsapp_phone') }}</div> @endif
        </div>
    </div>

    <div style="margin-top:16px">
        <label for="city" class="reg-label">Ville</label>
        <input id="city" name="city" value="{{ old('city') }}" autocomplete="address-level2" class="reg-input">
        @if ($errors->has('city') && session('_old_input')) <div class="reg-error">{{ $errors->first('city') }}</div> @endif
    </div>

    <div style="margin-top:20px">
        <button
            type="submit"
            id="submit-btn"
            class="reg-button"
            onclick="this.disabled=true;
                     this.innerText='Envoi en cours...';
                     this.form.submit();">
            Envoyer l'inscription
        </button>
    </div>
</form>
@endsection
