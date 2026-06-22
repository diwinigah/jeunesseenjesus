@extends('layouts.app')

@section('title', 'Demande de partenariat')

@push('styles')
<style>
    .part-req-container { width: min(100%, 600px); margin: 40px auto; }
    .part-req-header { margin-bottom: 24px; }
    .part-req-h1 { margin: 6px 0 10px; font-size: clamp(1.6rem, 4vw, 2.2rem); color: #333; }
    .part-req-intro { line-height: 1.55; color: #5d6678; }
    .part-req-form-box { background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(15, 23, 42, .08); }
    .part-req-group { margin-bottom: 20px; }
    .part-req-label { display: block; font-weight: 700; margin-bottom: 6px; font-size: .95rem; color: #333; }
    .part-req-input, .part-req-textarea, .part-req-select { width: 100%; padding: 10px 12px; border: 1px solid #dfe3ea; border-radius: 6px; font-family: inherit; font-size: .95rem; color: #333; }
    .part-req-input:focus, .part-req-textarea:focus, .part-req-select:focus { outline: none; border-color: #E8490F; box-shadow: 0 0 0 3px rgba(232, 73, 15, .1); }
    .part-req-textarea { resize: vertical; min-height: 120px; }
    .part-req-required { color: #dc2626; }
    .part-req-error { color: #dc2626; font-size: .85rem; margin-top: 4px; }
    .part-req-field-error { border-color: #dc2626; }
    .part-req-field-error:focus { box-shadow: 0 0 0 3px rgba(220, 38, 38, .1); }
    .part-req-helper { color: #667085; font-size: .85rem; margin-top: 4px; }
    .part-req-row { display: grid; grid-template-columns: 1fr; gap: 20px; }
    @media (min-width: 600px) { .part-req-row { grid-template-columns: 1fr 1fr; } }
    .part-req-btn { display: inline-flex; align-items: center; justify-content: center; min-height: 44px; padding: 0 24px; border: none; border-radius: 6px; font-size: .95rem; font-weight: 700; text-decoration: none; cursor: pointer; }
    .part-req-btn.primary { background: #E8490F; color: #fff; width: 100%; }
    .part-req-btn.primary:hover { background: #C73D0A; }
    .part-req-btn.primary:disabled { background: #ccc; cursor: not-allowed; }
    .part-req-btn.secondary { background: #f3f4f6; color: #333; border: 1px solid #dfe3ea; }
    .part-req-btn.secondary:hover { background: #e5e7eb; }
    .part-req-btn-group { display: flex; gap: 12px; margin-top: 24px; }
    .part-req-errors { background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
    .part-req-errors ul { margin: 8px 0 0; padding-left: 20px; }
</style>
@endpush

@section('content')
<div class="part-req-container">
    <div class="part-req-header">
        <h1 class="part-req-h1">Devenir partenaire</h1>
        <p class="part-req-intro">Remplissez le formulaire ci-dessous pour soumettre votre demande de partenariat. Notre équipe examinera votre demande et vous contactera bientôt.</p>
    </div>

    <section class="part-req-form-box">
        @if ($errors->any() && session('_old_input'))
            <div class="part-req-errors">
                <strong>Erreurs détectées :</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('partners.store') }}" novalidate data-recaptcha>
            @csrf

            <div class="part-req-row">
                <div class="part-req-group">
                    <label for="contact_name" class="part-req-label">
                        Nom du contact
                        <span class="part-req-required">*</span>
                    </label>
                    <input
                        type="text"
                        id="contact_name"
                        name="contact_name"
                        class="part-req-input {{ $errors->has('contact_name') && session('_old_input') ? 'part-req-field-error' : '' }}"
                        value="{{ old('contact_name') }}"
                        placeholder="Jean Dupont"
                        required
                    >
                    @if ($errors->has('contact_name') && session('_old_input'))
                        <div class="part-req-error">{{ $errors->first('contact_name') }}</div>
                    @endif
                </div>

                <div class="part-req-group">
                    <label for="organization_name" class="part-req-label">
                        Nom de l'organisation
                        <span class="part-req-required">*</span>
                    </label>
                    <input
                        type="text"
                        id="organization_name"
                        name="organization_name"
                        class="part-req-input {{ $errors->has('organization_name') && session('_old_input') ? 'part-req-field-error' : '' }}"
                        value="{{ old('organization_name') }}"
                        placeholder="Acme Corporation"
                        required
                    >
                    @if ($errors->has('organization_name') && session('_old_input'))
                        <div class="part-req-error">{{ $errors->first('organization_name') }}</div>
                    @endif
                </div>
            </div>

            <div class="part-req-row">
                <div class="part-req-group">
                    <label for="email" class="part-req-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="part-req-input {{ $errors->has('email') && session('_old_input') ? 'part-req-field-error' : '' }}"
                        value="{{ old('email') }}"
                        placeholder="contact@example.com"
                    >
                    @if ($errors->has('email') && session('_old_input'))
                        <div class="part-req-error">{{ $errors->first('email') }}</div>
                    @endif
                    <div class="part-req-helper">Optionnel</div>
                </div>

                <div class="part-req-group">
                    <label for="phone" class="part-req-label">
                        Téléphone
                        <span class="part-req-required">*</span>
                    </label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        class="part-req-input {{ $errors->has('phone') && session('_old_input') ? 'part-req-field-error' : '' }}"
                        value="{{ old('phone') }}"
                        placeholder="+221 77 123 45 67"
                        required
                    >
                    @if ($errors->has('phone') && session('_old_input'))
                        <div class="part-req-error">{{ $errors->first('phone') }}</div>
                    @endif
                </div>
            </div>

            <div class="part-req-row">
                <div class="part-req-group">
                    <label for="type" class="part-req-label">Type d'organisation</label>
                    <select
                        id="type"
                        name="type"
                        class="part-req-select {{ $errors->has('type') && session('_old_input') ? 'part-req-field-error' : '' }}"
                    >
                        <option value="">-- Sélectionner --</option>
                        <option value="church" {{ old('type') === 'church' ? 'selected' : '' }}>Église</option>
                        <option value="company" {{ old('type') === 'company' ? 'selected' : '' }}>Entreprise</option>
                        <option value="association" {{ old('type') === 'association' ? 'selected' : '' }}>Association</option>
                        <option value="individual" {{ old('type') === 'individual' ? 'selected' : '' }}>Individu</option>
                        <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @if ($errors->has('type') && session('_old_input'))
                        <div class="part-req-error">{{ $errors->first('type') }}</div>
                    @endif
                    <div class="part-req-helper">Optionnel</div>
                </div>

                <div class="part-req-group">
                    <label for="website_url" class="part-req-label">Site web</label>
                    <input
                        type="url"
                        id="website_url"
                        name="website_url"
                        class="part-req-input {{ $errors->has('website_url') && session('_old_input') ? 'part-req-field-error' : '' }}"
                        value="{{ old('website_url') }}"
                        placeholder="https://example.com"
                    >
                    @if ($errors->has('website_url') && session('_old_input'))
                        <div class="part-req-error">{{ $errors->first('website_url') }}</div>
                    @endif
                    <div class="part-req-helper">Optionnel</div>
                </div>
            </div>

            <div class="part-req-group">
                <label for="message" class="part-req-label">Message</label>
                <textarea
                    id="message"
                    name="message"
                    class="part-req-textarea {{ $errors->has('message') && session('_old_input') ? 'part-req-field-error' : '' }}"
                    placeholder="Décrivez votre demande et comment vous envisagez de collaborer avec nous..."
                >{{ old('message') }}</textarea>
                @if ($errors->has('message') && session('_old_input'))
                    <div class="part-req-error">{{ $errors->first('message') }}</div>
                @endif
                <div class="part-req-helper">Optionnel (max. 2000 caractères)</div>
            </div>

            <div class="part-req-btn-group">
                <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                <button
                    type="submit"
                    id="submit-btn"
                    class="part-req-btn primary"
                    onclick="this.disabled=true;
                             this.innerText='Envoi en cours...';
                             this.form.submit();">
                    Soumettre la demande
                </button>
                <a href="{{ route('partners.index') }}" class="part-req-btn secondary">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
