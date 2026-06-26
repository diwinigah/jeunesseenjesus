@extends('layouts.app')

@section('title', 'Inscription au camp')

@push('styles')
<style>
/* ═══════════════════════════════════
   PAGE INSCRIPTION — DESIGN PRO
═══════════════════════════════════ */

/* Container général */
.registration-page {
    max-width: 860px;
    margin: 0 auto;
    padding: 0 1rem 3rem;
}

/* Bannière cover */
.form-cover-banner {
    width: 100%;
    margin-bottom: 0;
    border-radius: 16px 16px 0 0;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}
.form-cover-banner img {
    width: 100%;
    max-height: 260px;
    object-fit: cover;
    display: block;
}

/* Card formulaire */
.registration-card {
    background: #fff;
    border-radius: 0 0 16px 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    padding: 2rem 2.5rem 2.5rem;
    margin-bottom: 2rem;
}

/* Si pas de bannière — coins arrondis partout */
.registration-card-alone {
    border-radius: 16px;
}

/* Titre page */
.registration-page h1 {
    font-size: 1.6rem;
    font-weight: 900;
    color: #3D2B1F;
    margin-bottom: 0.25rem;
}
.registration-page .edition-info {
    font-size: 0.88rem;
    color: #888;
    margin-bottom: 1.75rem;
    padding-bottom: 1.25rem;
    border-bottom: 2px solid #f0e8e4;
}

/* Description d'édition */
.registration-page .edition-description {
    font-size: 0.95rem;
    color: #3D2B1F;
    line-height: 1.6;
    margin-top: 1rem;
    padding: 1rem 0;
    border-top: 2px solid #f0e8e4;
    white-space: pre-wrap;
    word-wrap: break-word;
}

/* Séparateur de sections */
.form-section-title {
    font-size: 0.8rem;
    font-weight: 800;
    color: #E8490F;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin: 1.75rem 0 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.form-section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #f0e8e4;
}

/* Grille 2 colonnes */
.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem 1.25rem;
}
.form-grid-full {
    grid-column: 1 / -1;
}

/* Groupe de champ */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

/* Label */
.form-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: #3D2B1F;
}
.form-optional {
    font-weight: 400;
    color: #aaa;
    font-size: 0.78rem;
}
.form-required {
    color: #E8490F;
    margin-left: 0.15rem;
}

/* Inputs */
.form-input {
    width: 100%;
    padding: 0.65rem 0.9rem;
    border: 1.5px solid #e2d8d3;
    border-radius: 10px;
    font-size: 0.92rem;
    color: #2d1f17;
    background: #fafafa;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
}
.form-input:focus {
    border-color: #E8490F;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(232, 73, 15, 0.1);
}
.form-input.input-error {
    border-color: #e53e3e;
    background: #fff5f5;
}

/* Select */
select.form-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23888'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 18px;
    padding-right: 2.25rem;
    cursor: pointer;
}

/* Textarea */
textarea.form-input {
    resize: vertical;
    min-height: 90px;
}

/* Helper text */
.form-helper {
    font-size: 0.75rem;
    color: #999;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}
.form-helper strong { color: #E8490F; }

/* Messages d'erreur */
.form-error {
    font-size: 0.78rem;
    color: #e53e3e;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Bloc erreurs global */
.form-errors-block {
    background: #fff5f5;
    border: 1.5px solid #fed7d7;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
}
.form-errors-block p {
    font-size: 0.85rem;
    font-weight: 700;
    color: #c53030;
    margin-bottom: 0.5rem;
}
.form-errors-block ul {
    margin: 0;
    padding-left: 1.25rem;
}
.form-errors-block li {
    font-size: 0.82rem;
    color: #c53030;
    margin-bottom: 0.2rem;
}

/* Jours de présence */
.days-checkboxes {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    margin-top: 0.25rem;
}
.day-checkbox {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.88rem;
    color: #3D2B1F;
    cursor: pointer;
    background: #f9f3f0;
    padding: 0.4rem 0.75rem;
    border-radius: 20px;
    border: 1.5px solid #e2d8d3;
    transition: border-color 0.2s, background 0.2s;
}
.day-checkbox input[type="checkbox"] {
    accent-color: #E8490F;
    width: 15px;
    height: 15px;
    cursor: pointer;
}
.day-checkbox:has(input:checked) {
    border-color: #E8490F;
    background: #fff3ef;
}

/* Radio buttons */
.radio-group {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 0.25rem;
}
.radio-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #3D2B1F;
    cursor: pointer;
    background: #f9f3f0;
    padding: 0.5rem 1.1rem;
    border-radius: 20px;
    border: 1.5px solid #e2d8d3;
    transition: border-color 0.2s, background 0.2s;
}
.radio-option input[type="radio"] {
    accent-color: #E8490F;
    width: 15px;
    height: 15px;
    cursor: pointer;
}
.radio-option:has(input:checked) {
    border-color: #E8490F;
    background: #fff3ef;
    font-weight: 600;
}

/* Lien activités */
.activities-link-block {
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}
.activities-link-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #fdf6f3;
    border: 1.5px solid #E8490F;
    color: #E8490F;
    padding: 0.55rem 1.1rem;
    border-radius: 25px;
    font-size: 0.88rem;
    font-weight: 700;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}
.activities-link-btn:hover {
    background: #E8490F;
    color: #fff;
}
.activities-link-btn svg {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
}

/* Inscription externe */
.external-registration-block {
    text-align: center;
    padding: 3rem 1rem;
}
.external-registration-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    background: linear-gradient(135deg, #E8490F, #ff6b35);
    color: #fff;
    padding: 1rem 2rem;
    border-radius: 14px;
    font-size: 1.05rem;
    font-weight: 800;
    text-decoration: none;
    box-shadow: 0 4px 18px rgba(232,73,15,0.3);
    transition: opacity 0.2s, transform 0.15s;
    margin-bottom: 0.75rem;
}
.external-registration-btn:hover {
    opacity: 0.92;
    transform: translateY(-2px);
    color: #fff;
}
.external-registration-btn svg {
    width: 18px;
    height: 18px;
}
.external-registration-note {
    font-size: 0.82rem;
    color: #aaa;
    margin-top: 0.75rem;
}

/* Bouton submit */
.form-submit-btn {
    width: 100%;
    padding: 0.9rem 2rem;
    background: linear-gradient(135deg, #E8490F, #ff6b35);
    color: #fff;
    font-size: 1rem;
    font-weight: 800;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    margin-top: 2rem;
    transition: opacity 0.2s, transform 0.1s;
    letter-spacing: 0.02em;
    box-shadow: 0 4px 16px rgba(232,73,15,0.3);
}
.form-submit-btn:hover {
    opacity: 0.93;
    transform: translateY(-1px);
}
.form-submit-btn:active {
    transform: translateY(0);
    opacity: 1;
}
.form-submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Lien annuler */
.form-cancel-link {
    display: block;
    text-align: center;
    margin-top: 1rem;
    font-size: 0.85rem;
    color: #aaa;
    text-decoration: none;
    transition: color 0.2s;
}
.form-cancel-link:hover { color: #3D2B1F; }

/* ═══════════════════════════════════
   RESPONSIVE
═══════════════════════════════════ */
@media (max-width: 640px) {
    .registration-card {
        padding: 1.25rem 1rem 1.75rem;
        border-radius: 0 0 12px 12px;
    }
    .form-grid-2 {
        grid-template-columns: 1fr;
    }
    .form-grid-full {
        grid-column: 1;
    }
    .registration-page h1 { font-size: 1.3rem; }
    .days-checkboxes { gap: 0.4rem; }
    .day-checkbox { font-size: 0.82rem; padding: 0.35rem 0.6rem; }
    .radio-group { gap: 0.6rem; }
    .form-cover-banner img { max-height: 180px; }
    .form-submit-btn { font-size: 0.95rem; padding: 0.85rem; }
}
</style>
@endpush

@section('content')
<div class="registration-page">
@if($edition->cover_image_path)
    <div class="form-cover-banner">
        <img alt="Bannière {{ $edition->name }}" src="{{ Storage::url($edition->cover_image_path) }}">
    </div>
@endif
<div class="reg-header">
    <h1>{{ $edition->registration_page_title ?: 'Inscription Evenement' }}</h1>
    <p>{{ $edition->name }} - inscriptions ouvertes jusqu'au {{ $edition->registration_close_at->format('d/m/Y H:i') }}.</p>
    @if($edition->description)
        <div class="edition-description">{{ $edition->description }}</div>
    @endif

    {{-- LIEN ACTIVITÉS (toujours visible, après la description) --}}
    @if($edition->activities_link_url && $edition->activities_link_label)
    <div class="activities-link-block">
        <a href="{{ $edition->activities_link_url }}"
           target="_blank"
           rel="noopener noreferrer"
           class="activities-link-btn">
            {{ $edition->activities_link_label }}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
        </a>
    </div>
    @endif
</div>

@if ($errors->any() && session('_old_input'))
    <div class="form-errors-block">Merci de corriger les champs indiques.</div>
@endif

{{-- MODE D'INSCRIPTION --}}
@if($edition->registration_mode === 'external' && $edition->external_registration_url)

    {{-- 2ÈME MÉTHODE : LIEN EXTERNE --}}
    <div class="external-registration-block">
        <a href="{{ $edition->external_registration_url }}"
           target="_blank"
           rel="noopener noreferrer"
           class="external-registration-btn">
            {{ $edition->external_registration_label ?? 'S\'inscrire' }}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
        </a>
        <p class="external-registration-note">
            Vous serez redirigé vers le formulaire d'inscription externe.
        </p>
    </div>

@else

    {{-- 1ÈRE MÉTHODE : FORMULAIRE INTERNE (actuel) --}}
    <form method="POST" action="{{ route('registration.store') }}" class="registration-card animate-left" data-recaptcha>
        @csrf

    <div class="form-grid-2">
        <div class="form-group animate-left">
            <label for="first_name" class="form-label">Prenom</label>
            <input id="first_name" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" class="reg-input form-input">
            @if ($errors->has('first_name') && session('_old_input')) <div class="form-error">{{ $errors->first('first_name') }}</div> @endif
        </div>

        <div class="form-group animate-left">
            <label for="last_name" class="form-label">Nom</label>
            <input id="last_name" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name" class="reg-input form-input">
            @if ($errors->has('last_name') && session('_old_input')) <div class="form-error">{{ $errors->first('last_name') }}</div> @endif
        </div>
    </div>

    <div class="form-grid-2" style="margin-top:16px">
        <div class="form-group animate-left">
            <label for="gender" class="form-label">Genre</label>
            <select id="gender" name="gender" required class="reg-input form-input">
                <option value="">Choisir</option>
                <option value="male" @selected(old('gender') === 'male')>Homme</option>
                <option value="female" @selected(old('gender') === 'female')>Femme</option>
                <option value="other" @selected(old('gender') === 'other')>Autre</option>
            </select>
            @if ($errors->has('gender') && session('_old_input')) <div class="form-error">{{ $errors->first('gender') }}</div> @endif
        </div>

        <div class="form-group animate-left">
            <label for="edition_section_id" class="form-label">Quelle est votre section ?</label>
            <div class="form-helper">(ignorez si vous avez été invité)</div>
            <select id="edition_section_id" name="edition_section_id" class="reg-input form-input">
                <option value="">-- Aucune / Invité --</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}" @selected((string) old('edition_section_id') === (string) $section->id)>
                        {{ $section->section->label() }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('edition_section_id') && session('_old_input')) <div class="form-error">{{ $errors->first('edition_section_id') }}</div> @endif
        </div>
    </div>

    <div class="form-grid-2" style="margin-top:16px">
        <div class="form-group animate-left">
            <label for="phone" class="form-label">Telephone</label>
            <input id="phone" name="phone" type="tel" inputmode="tel" pattern="[\+0-9\s\-\(\)]{7,20}" value="{{ old('phone') }}" required autocomplete="tel" class="reg-input form-input">
            @if ($errors->has('phone') && session('_old_input')) <div class="form-error">{{ $errors->first('phone') }}</div> @endif
            <p class="form-helper">
                📞 Indicatif international accepté (ex: +228, +33).<br>
                <strong>NB: Numéro valide exigé .</strong>
            </p>
        </div>

        <div class="form-group animate-left">
            <label for="whatsapp_phone" class="form-label">WhatsApp</label>
            <input id="whatsapp_phone" name="whatsapp_phone" type="tel" inputmode="tel" pattern="[\+0-9\s\-\(\)]{7,20}" value="{{ old('whatsapp_phone') }}" autocomplete="tel" class="reg-input form-input">
            <div class="form-helper">Facultatif.</div>
            @if ($errors->has('whatsapp_phone') && session('_old_input')) <div class="form-error">{{ $errors->first('whatsapp_phone') }}</div> @endif
        </div>
    </div>

    <div class="form-group animate-left" style="margin-top:16px">
        <label for="city" class="form-label">Ville</label>
        <input id="city" name="city" value="{{ old('city') }}" autocomplete="address-level2" class="reg-input form-input">
        @if ($errors->has('city') && session('_old_input')) <div class="form-error">{{ $errors->first('city') }}</div> @endif
    </div>

    @if($edition->show_days_presence)
        <div class="form-group animate-left" style="margin-top:16px">
            <label class="form-label">Combien de jours passerez-vous au camp ?</label>
            <div class="days-checkboxes">
                @foreach(['jour_1'=>'Jour 1','jour_2'=>'Jour 2','jour_3'=>'Jour 3','jour_4'=>'Jour 4','jour_5'=>'Jour 5','jour_6'=>'Jour 6'] as $key => $label)
                    <label class="day-checkbox">
                        <input class="form-input" type="checkbox" name="days_presence[]" value="{{ $key }}" @checked(in_array($key, old('days_presence', [])))>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @if ($errors->has('days_presence') && session('_old_input')) <div class="form-error">{{ $errors->first('days_presence') }}</div> @endif
        </div>
    @endif

    @if($edition->show_children_count)
        <div class="form-group animate-left" style="margin-top:16px">
            <label for="children_count" class="form-label">Nombre d'enfants accompagnateurs <span class="form-helper">(facultatif)</span></label>
            <input id="children_count" name="children_count" type="number" min="0" max="20" value="{{ old('children_count') }}" class="reg-input form-input">
            @if ($errors->has('children_count') && session('_old_input')) <div class="form-error">{{ $errors->first('children_count') }}</div> @endif
        </div>
    @endif

    @if($edition->show_bus_departure)
        <div class="form-group animate-left" style="margin-top:16px">
            <label class="form-label">Départ avec le bus ? *</label>
            <div class="radio-group">
                <label>
                    <input class="form-input" type="radio" name="bus_departure" value="1" @checked(old('bus_departure') === '1') required> Oui
                </label>
                <label>
                    <input class="form-input" type="radio" name="bus_departure" value="0" @checked(old('bus_departure') === '0')> Non
                </label>
            </div>
            @if ($errors->has('bus_departure') && session('_old_input')) <div class="form-error">{{ $errors->first('bus_departure') }}</div> @endif
        </div>
    @endif

    @if($edition->show_participant_type)
        <div class="form-group animate-left" style="margin-top:16px">
            <label for="participant_type" class="form-label">Vous êtes... *</label>
            <select id="participant_type" name="participant_type" required class="reg-input form-input">
                <option value="">-- Sélectionner --</option>
                <option value="eleve" @selected(old('participant_type') === 'eleve')>Élève</option>
                <option value="etudiant" @selected(old('participant_type') === 'etudiant')>Étudiant</option>
                <option value="adulte" @selected(old('participant_type') === 'adulte')>Adulte</option>
            </select>
            @if ($errors->has('participant_type') && session('_old_input')) <div class="form-error">{{ $errors->first('participant_type') }}</div> @endif
        </div>
    @endif

    <div style="margin-top:20px">
        <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
        <button
            type="submit"
            id="submit-btn"
            class="form-submit-btn"
            onclick="this.disabled=true;
                     this.innerText='Envoi en cours...';
                     this.form.submit();">
            Envoyer l'inscription
        </button>
    </div>
</form>

@endif
</div>
@endsection

