@extends('layouts.app')

@section('title', 'Sponsoring ' . ($edition?->name ?? 'Camp'))

@push('styles')

<style>
/* ═══════════════════════════════════
   BASE
═══════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; }

.sp-page {
    max-width: 960px;
    margin: 0 auto;
    padding: 0 1rem 4rem;
    overflow-x: hidden;
}

.sp-section {
    margin-bottom: 2.5rem;
    width: 100%;
}

.sp-section-title {
    font-size: 1.05rem;
    font-weight: 800;
    color: #3D2B1F;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 0.6rem;
    border-bottom: 2px solid #f0e8e4;
}

.sp-section-title .sp-icon svg { width: 20px; height: 20px; color: #E8490F; }

/* ═══════════════════════════════════
   HERO
═══════════════════════════════════ */
.sp-hero {
    position: relative;
    border-radius: 16px;
    overflow: visible;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
}
.sp-hero-img {
    position: relative;
    overflow: hidden;
    border-radius: 32px;
    height: 260px;
}
.sp-hero img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    filter: brightness(0.65);
    
}
.sp-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to bottom, rgba(61,43,31,0.2), rgba(61,43,31,0.82));
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem 1rem 2rem 1rem;
    text-align: center;
    min-height: 260px;
}
.sp-hero-tag {
    background: #E8490F;
    color: #fff;
    padding: 0.25rem 1rem;
    border-radius: 30px;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-bottom: 0.75rem;
}
.sp-hero-title {
    font-size: 1.8rem;
    font-weight: 900;
    color: #fff;
    margin: 0 0 0.4rem;
    text-shadow: 0 2px 8px rgba(0,0,0,0.4);
    word-break: break-word;
}
.sp-hero-theme {
    font-size: 1rem;
    color: #f9c97c;
    font-style: italic;
    font-weight: 600;
    margin: 0.2rem 0;
}
.sp-hero-verse {
    font-size: 0.85rem;
    color: rgba(255,255,255,0.85);
    margin: 0.4rem 0;
}
.sp-hero-dates {
    font-size: 0.88rem;
    color: #fff;
    margin-top: 0.6rem;
    background: rgba(255,255,255,0.15);
    padding: 0.35rem 0.9rem;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    backdrop-filter: blur(4px);
}
.sp-hero-dates .sp-icon svg {
    width: 16px !important;
    height: 16px !important;
    color: #fff;
    flex-shrink: 0;
}
.sp-hero-no-img {
    background: linear-gradient(135deg, #3D2B1F 0%, #6b3a25 100%);
    padding: 2.5rem 1.5rem;
    text-align: center;
    border-radius: 16px;
    margin-bottom: 2rem;
}

/* ═══════════════════════════════════
   INTRO
═══════════════════════════════════ */
.sp-intro {
    font-size: 0.95rem;
    line-height: 1.85;
    color: #444;
    background: #fdf6f3;
    border-left: 4px solid #E8490F;
    padding: 1.25rem 1.25rem;
    border-radius: 0 12px 12px 0;
    word-break: break-word;
}

.sp-salutation {
    font-size: 1rem;
    font-weight: 700;
    color: #3D2B1F;
    margin-bottom: 0.6rem;
}

/* ═══════════════════════════════════
   PROGRESSION
═══════════════════════════════════ */
.sp-progress-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.sp-progress-card {
    background: #fff;
    border: 1px solid #f0e8e4;
    border-radius: 14px;
    padding: 1.25rem;
    width: 100%;
}
.sp-progress-top {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
    margin-bottom: 0.75rem;
}
.sp-progress-label { font-size: 0.85rem; color: #666; font-weight: 600; }
.sp-progress-value { font-size: 0.9rem; font-weight: 800; color: #3D2B1F; }
.sp-progress-track {
    background: #f0e8e4;
    border-radius: 99px;
    height: 12px;
    overflow: hidden;
    width: 100%;
}
.sp-progress-fill {
    background: linear-gradient(90deg, #E8490F, #ff6b35);
    height: 100%;
    border-radius: 99px;
    transition: width 1s ease;
}
.sp-progress-fill-green { background: linear-gradient(90deg, #16a34a, #22c55e); }
.sp-progress-pct {
    font-size: 0.8rem;
    color: #E8490F;
    font-weight: 700;
    text-align: right;
    margin-top: 0.35rem;
    display: block;
}

/* ═══════════════════════════════════
   BOURSES DUO
═══════════════════════════════════ */
.sp-bourse-duo {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.sp-bourse-card {
    background: #fff;
    border: 2px solid #f0e8e4;
    border-radius: 14px;
    padding: 1.5rem 1rem;
    text-align: center;
    transition: transform 0.2s, box-shadow 0.2s;
    min-width: 0; /* empêche débordement */
}
.sp-bourse-featured { border-color: #E8490F; background: linear-gradient(135deg, #fff8f6, #fff); }
.sp-bourse-libre { border-color: #f9c97c; background: linear-gradient(135deg, #fffdf5, #fff); }
.sp-bourse-icon { font-size: 1.8rem; margin-bottom: 0.5rem; }
.sp-bourse-icon .sp-icon svg { width: 32px; height: 32px; color: #E8490F; }
.sp-bourse-libre .sp-bourse-icon .sp-icon svg { color: #d4a017; }
.sp-bourse-card h4 { font-size: 0.9rem; font-weight: 800; color: #3D2B1F; margin-bottom: 0.35rem; }
.sp-bourse-card p { font-size: 0.75rem; color: #888; margin-bottom: 0.5rem; line-height: 1.4; }
.sp-bourse-amount { font-size: 1.2rem; font-weight: 900; color: #E8490F; white-space: nowrap; }
.sp-bourse-amount small { font-size: 0.68rem; font-weight: 600; color: #aaa; display: block; margin-top: 0.2rem; }
.sp-bourse-libre-amount { color: #d4a017 !important; }

/* ═══════════════════════════════════
   CATÉGORIES
═══════════════════════════════════ */
.sp-categorie-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}
.sp-categorie-card {
    background: #fff;
    border: 1px solid #f0e8e4;
    border-radius: 14px;
    padding: 1rem 0.5rem;
    text-align: center;
    min-width: 0;
}
.sp-categorie-card h4 { font-size: 0.82rem; font-weight: 0.82rem; color: #3D2B1F; margin: 0.4rem 0 0.35rem; }
.sp-categorie-card .sp-bourse-amount { font-size: 0.95rem; white-space: nowrap; }

/* ═══════════════════════════════════
   RÉPARTITION
═══════════════════════════════════ */
.sp-repartition {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.sp-rep-card {
    background: #fff;
    border: 1px solid #f0e8e4;
    border-radius: 14px;
    padding: 1.25rem;
    min-width: 0;
}
.sp-rep-card h4 { font-size: 0.85rem; font-weight: 700; color: #3D2B1F; margin-bottom: 0.75rem; }
.sp-rep-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.45rem 0;
    border-bottom: 1px solid #f9f3f0;
    font-size: 0.85rem;
    gap: 0.5rem;
}
.sp-rep-row:last-child { border-bottom: none; }
.sp-rep-row span { color: #555; }
.sp-rep-row strong { color: #3D2B1F; font-weight: 700; white-space: nowrap; }
.sp-rep-total {
    background: #fdf6f3;
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    margin-top: 0.5rem;
    display: flex;
    justify-content: space-between;
    font-weight: 800;
    color: #E8490F;
    font-size: 0.9rem;
}

/* ═══════════════════════════════════
   NATURE
═══════════════════════════════════ */
.sp-nature-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}
.sp-nature-card {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    background: #fdf6f3;
    border-radius: 12px;
    padding: 0.9rem 1rem;
    border: 1px solid #f0e8e4;
    min-width: 0;
}
.sp-nature-num {
    background: #E8490F;
    color: #fff;
    border-radius: 50%;
    min-width: 26px;
    height: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 800;
    flex-shrink: 0;
}
.sp-nature-card p {
    font-size: 0.85rem;
    color: #444;
    margin: 0;
    line-height: 1.5;
    word-break: break-word;
    min-width: 0;
}

/* ═══════════════════════════════════
   PAIEMENT
═══════════════════════════════════ */
.sp-payment-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}
.sp-payment-card {
    background: #fff;
    border: 1px solid #f0e8e4;
    border-radius: 14px;
    padding: 1.25rem 0.75rem;
    text-align: center;
    min-width: 0;
    word-break: break-word;
}
.sp-payment-icon .sp-icon svg { width: 36px; height: 36px; color: #3D2B1F; }
.sp-payment-card h4 { font-weight: 800; color: #3D2B1F; margin-bottom: 0.4rem; font-size: 0.88rem; }
.sp-payment-card p { font-size: 0.8rem; color: #555; margin: 0.2rem 0; line-height: 1.5; word-break: break-all; }
.sp-payment-btn {
    display: inline-block;
    margin-top: 0.6rem;
    background: #E8490F;
    color: #fff;
    padding: 0.45rem 1rem;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 700;
    text-decoration: none;
    transition: background 0.2s;
    white-space: nowrap;
}
.sp-payment-btn:hover { background: #c73d0d; color: #fff; }

/* ═══════════════════════════════════
   BUDGET LINK / EXTERNAL LINKS
═══════════════════════════════════ */
.sp-external-links {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    justify-content: center;
    padding: 0.5rem 0;
}
.sp-budget-link-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    background: #fdf6f3;
    border: 2px solid #E8490F;
    color: #E8490F;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-size: 0.92rem;
    font-weight: 700;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
    font-family: 'Raleway', sans-serif;
}
.sp-budget-link-btn:hover {
    background: #E8490F;
    color: #fff;
}
.sp-budget-link-btn svg {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

/* ═══════════════════════════════════
   CONTACT
═══════════════════════════════════ */
.sp-contact {
    background: linear-gradient(135deg, #3D2B1F, #6b3a25);
    border-radius: 16px;
    padding: 2rem 1.25rem;
    text-align: center;
    color: #fff;
}
.sp-contact h3 { color: #fff; font-size: 1.1rem; font-weight: 800; margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
.sp-contact h3 .sp-icon svg { width: 20px; height: 20px; color: #fff; }
.sp-contact-items { display: flex; flex-wrap: wrap; justify-content: center; gap: 1.25rem; }
.sp-contact-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 600; color: #fff; word-break: break-word; }
.sp-contact-item .sp-icon svg { width: 18px; height: 18px; color: #fff; }

/* ═══════════════════════════════════
   CLOSED
═══════════════════════════════════ */
.sp-closed { text-align: center; padding: 4rem 1rem; }
.sp-closed-icon .sp-icon svg { width: 56px; height: 56px; color: #E8490F; margin-bottom: 1rem; }
.sp-closed h1 { color: #3D2B1F; font-size: 1.4rem; margin-bottom: 0.75rem; }
.sp-closed p { color: #888; }

/* ═══════════════════════════════════
   RESPONSIVE TABLETTE
═══════════════════════════════════ */
@media (max-width: 768px) {
    .sp-hero-title { font-size: 1.4rem; }
    .sp-repartition { grid-template-columns: 1fr; }
    .sp-categorie-grid { grid-template-columns: repeat(2, 1fr); }
    .sp-nature-grid { grid-template-columns: 1fr; }
    .sp-payment-grid { grid-template-columns: repeat(2, 1fr); }
}

/* ═══════════════════════════════════
   RESPONSIVE MOBILE
═══════════════════════════════════ */
@media (max-width: 480px) {
    .sp-page { padding: 0 0.75rem 3rem; }
    .sp-hero-img {
        height: 300px;
        
    }
    .sp-hero-overlay {
        padding: 1.75rem 1rem 2rem 1rem;
        min-height: 300px;
    }
    .sp-hero img {
        object-position: center center;
        
    }
    .sp-hero-title { font-size: 1.2rem; }
    .sp-hero-theme { font-size: 0.88rem; }
    .sp-hero-verse {
        font-size: 0.82rem;
        margin: 0.5rem 0;
        line-height: 1.4;
    }
    .sp-hero-dates {
        font-size: 0.78rem;
        padding: 0.3rem 0.75rem;
        white-space: normal;
        text-align: center;
        display: inline-flex;
        flex-wrap: wrap;
        justify-content: center;

    }
    .sp-hero-dates .sp-icon svg {
        width: 14px !important;
        height: 14px !important;
    }
    .sp-bourse-duo { grid-template-columns: 1fr; }
    .sp-bourse-card { padding: 1.25rem 1rem; }
    .sp-categorie-grid { grid-template-columns: repeat(2, 1fr); }
    .sp-payment-grid { grid-template-columns: 1fr; }
    .sp-nature-grid { grid-template-columns: 1fr; }
    .sp-contact { padding: 1.5rem 1rem; border-radius: 12px; }
    .sp-contact-items { flex-direction: column; gap: 0.75rem; }
    .sp-section-title { font-size: 0.95rem; }
    .sp-repartition { grid-template-columns: 1fr; }
    .sp-progress-value { font-size: 0.82rem; }
}

</style>
@endpush

@section('content')

@if(!$edition)
    <div class="sp-closed">
        <div class="sp-closed-icon"><span class="sp-icon"> 
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 00-7 7v1a2 2 0 01-2 2v1a2 2 0 002 2 7 7 0 007 7 7 7 0 007-7 7 7 0 00-7-7z"/></svg>
        </span></div>
        <h1>Page de sponsoring</h1>
        <p>Aucune campagne de sponsoring active pour le moment. Revenez bientôt !</p>
    </div>

@else

<div class="sp-page">

    {{-- HERO --}}
    @if($edition->cover_image_path)
    <div class="sp-hero">
        <div class="sp-hero-img">
            <img src="{{ Storage::url($edition->cover_image_path) }}" alt="{{ $edition->name }}">
        </div>
        <div class="sp-hero-overlay">
            <div class="sp-hero-tag">Sponsoring</div>
            <h2 class="sp-hero-title">{{ $edition->name }}</h2>
            @if($edition->sponsoring_theme)
                <div class="sp-hero-theme">« {{ $edition->sponsoring_theme }} »</div>
            @endif
            @if($edition->sponsoring_verse)
                <div class="sp-hero-verse">{{ $edition->sponsoring_verse }}</div>
            @endif
            @if($edition->camp_start_date && $edition->camp_end_date)
                <div class="sp-hero-dates"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                </span> {{ \Carbon\Carbon::parse($edition->camp_start_date)->translatedFormat('d F') }}
                —
                {{ \Carbon\Carbon::parse($edition->camp_end_date)->translatedFormat('d F Y') }}</div>
            @endif
        </div>
    </div>
    @else
    <div class="sp-hero-no-img">
        <div class="sp-hero-tag">Sponsoring</div>
        <h2 class="sp-hero-title">{{ $edition->name }}</h2>
        @if($edition->sponsoring_theme)
            <div class="sp-hero-theme">« {{ $edition->sponsoring_theme }} »</div>
        @endif
    </div>
    @endif

    {{-- INTRO --}}
    @if($edition->sponsoring_salutation)
    <div class="sp-section"><div class="sp-salutation">{{ e($edition->sponsoring_salutation) }}</div></div>
    @endif

    {{-- INTRO --}}
    @if($edition->sponsoring_intro)
    <div class="sp-section sp-intro">{!! nl2br(html_entity_decode(e($edition->sponsoring_intro))) !!}</div>
    @endif

    {{-- PROGRESSION & BOURSES --}}
    <div class="sp-section sp-progress-grid">
        <div class="sp-progress-card">
            <div class="sp-progress-top">
                <div class="sp-progress-label">Budget collecté</div>
                <div class="sp-progress-value">{{ number_format($edition->budget_collected ?? 0, 0, ',', ' ') }} / {{ number_format($edition->budget_total ?? 0, 0, ',', ' ') }} FCFA</div>
            </div>
            @php $budgetPct = ($edition->budget_total ?? 0) > 0 ? min(100, round((($edition->budget_collected ?? 0) / $edition->budget_total) * 100)) : 0; @endphp
            <div class="sp-progress-track"><div class="sp-progress-fill" style="width: {{ $budgetPct }}%;"></div></div>
            <span class="sp-progress-pct">{{ $budgetPct }}% atteint</span>
    </div>

    

    {{-- BOURSES DÉTAILLÉES --}}
    @if($edition->bourse_pleine_label || $edition->bourse_partielle_label)
    <div class="sp-section">
        <div class="sp-section-title"><span class="sp-icon"> 
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
        </span> {{ $edition->title_bourses ?: 'Types de bourses' }}</div>

        <div class="sp-bourse-duo">
            @if($edition->bourse_pleine_label)
            <div class="sp-bourse-card sp-bourse-featured">
                <div class="sp-bourse-icon"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.153c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.95c.3.921-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.176 0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.287-3.95a1 1 0 00-.364-1.118L2.07 9.377c-.783-.57-.38-1.81.588-1.81h4.153a1 1 0 00.95-.69l1.286-3.95z"/></svg>
                </span></div>
                <h4>{{ $edition->bourse_pleine_label }}</h4>
                <p>{{ $edition->bourse_pleine_desc }}</p>
                @if($edition->bourse_pleine_amount !== null)
                <div class="sp-bourse-amount">{{ number_format($edition->bourse_pleine_amount, 0, ',', ' ') }} FCFA <small>par campeur</small></div>
                @endif
            </div>
            @endif

            @if($edition->bourse_partielle_label)
            <div class="sp-bourse-card sp-bourse-libre">
                <div class="sp-bourse-icon"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21s-8-4.35-8-10a5 5 0 0110 0 5 5 0 0110 0c0 5.65-8 10-8 10z"/></svg>
                </span></div>
                <h4>{{ $edition->bourse_partielle_label }}</h4>
                <p>{{ $edition->bourse_partielle_desc }}</p>
                <div class="sp-bourse-amount sp-bourse-libre-amount">Libre</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- FRAIS DE PARTICIPATION PAR CATÉGORIE --}}
    @if($edition->categorie_adulte_label || $edition->categorie_etudiant_label || $edition->categorie_lycee_label || $edition->categorie_enfant_label)
    <div class="sp-section">
        <div class="sp-section-title"><span class="sp-icon"> 
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
        </span> {{ $edition->title_frais ?: 'Frais de participation par catégorie' }}</div>

        <div class="sp-categorie-grid">
            @if($edition->bourse_adulte_amount !== null && $edition->bourse_adulte_amount > 0 && $edition->categorie_adulte_label)
                <div class="sp-categorie-card">
                    <h4>{{ $edition->categorie_adulte_label }}</h4>
                    <div class="sp-bourse-amount">{{ number_format($edition->bourse_adulte_amount, 0, ',', ' ') }} FCFA</div>
                </div>
            @endif

            @if($edition->bourse_etudiant_amount !== null && $edition->bourse_etudiant_amount > 0 && $edition->categorie_etudiant_label)
                <div class="sp-categorie-card">
                    <h4>{{ $edition->categorie_etudiant_label }}</h4>
                    <div class="sp-bourse-amount">{{ number_format($edition->bourse_etudiant_amount, 0, ',', ' ') }} FCFA</div>
                </div>
            @endif

            @if($edition->bourse_lycee_amount !== null && $edition->bourse_lycee_amount > 0 && $edition->categorie_lycee_label)
                <div class="sp-categorie-card">
                    <h4>{{ $edition->categorie_lycee_label }}</h4>
                    <div class="sp-bourse-amount">{{ number_format($edition->bourse_lycee_amount, 0, ',', ' ') }} FCFA</div>
                </div>
            @endif

            @if($edition->bourse_enfant_amount !== null && $edition->bourse_enfant_amount > 0 && $edition->categorie_enfant_label)
                <div class="sp-categorie-card">
                    <h4>{{ $edition->categorie_enfant_label }}</h4>
                    <div class="sp-bourse-amount">{{ number_format($edition->bourse_enfant_amount, 0, ',', ' ') }} FCFA</div>
                </div>
            @endif
        </div>
    </div>
    @endif



    {{-- APPORTS EN NATURE --}}
    @if($edition->nature_contributions && count($edition->nature_contributions) > 0)
        <div class="sp-section">
        <div class="sp-section-title"><span class="sp-icon"> 
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7l9-4 9 4"/><path d="M21 7v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7"/></svg>
        </span> {{ $edition->title_nature ?: 'Apports en nature' }}</div>
        <div class="sp-nature-grid">
                    @foreach($edition->nature_contributions as $index => $item)
                        <div class="sp-nature-card">
                            <div class="sp-nature-num"><span class="sp-icon"> 
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4v16"/><path d="M6 8v8"/><path d="M18 8v8"/></svg>
                            </span>{{ $index + 1 }}</div>
                            <p>{!! is_array($item) && isset($item['designation']) ? html_entity_decode(e($item['designation'])) : html_entity_decode(e($item)) !!}</p>
                        </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- MOYENS DE PAIEMENT --}}
    @if($edition->payment_flooz || $edition->payment_mixx || $edition->payment_account_name || $edition->payment_paypal)
        <div class="sp-section">
        <div class="sp-section-title"><span class="sp-icon"> 
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
        </span> {{ $edition->title_paiement ?: 'Comment contribuer ?' }}</div>
        <div class="sp-payment-grid">
            @if($edition->payment_flooz)
            <div class="sp-payment-card">
                <div class="sp-payment-icon"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 16V8a2 2 0 00-2-2h-3l-2-3H10L8 6H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2z"/></svg>
                </span></div>
                <h4>Flooz</h4>
                <p>{{ e($edition->payment_flooz) }}</p>
            </div>
            @endif

            @if($edition->payment_mixx)
            <div class="sp-payment-card">
                <div class="sp-payment-icon"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 16V8a2 2 0 00-2-2h-3l-2-3H10L8 6H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2z"/></svg>
                </span></div>
                <h4>Mixx by YAS</h4>
                <p>{{ e($edition->payment_mixx) }}</p>
            </div>
            @endif

            @if($edition->payment_account_name || $edition->payment_account_number)
            <div class="sp-payment-card">
                <div class="sp-payment-icon"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18"/><path d="M4 10h16v11H4z"/><path d="M12 4l8 6H4l8-6z"/></svg>
                </span></div>
                <h4>Virement bancaire</h4>
                @if($edition->payment_account_name)
                    <p><strong>Compte :</strong> {{ e($edition->payment_account_name) }}</p>
                @endif
                @if($edition->payment_account_number)
                    <p><strong>N° :</strong> {{ e($edition->payment_account_number) }}</p>
                @endif
                @if($edition->payment_iban)
                    <p><strong>IBAN :</strong> {{ e($edition->payment_iban) }}</p>
                @endif
            </div>
            @endif

            @if($edition->payment_paypal)
            <div class="sp-payment-card">
                <div class="sp-payment-icon"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7h18v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/><path d="M16 3l-1 4"/><path d="M8 3l1 4"/></svg>
                </span></div>
                <h4>PayPal</h4>
                <p><a class="sp-payment-btn" href="{{ e($edition->payment_paypal) }}" target="_blank" rel="noopener">Contribuer via PayPal →</a></p>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Liens externes (après Comment contribuer ?) --}}
    @if($edition->external_links && count($edition->external_links) > 0)
    <div class="sp-section">
        <div class="sp-external-links">
            @foreach($edition->external_links as $link)
                @if(!empty($link['url']) && !empty($link['label']))
                <a href="{{ $link['url'] }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="sp-budget-link-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                    {{ html_entity_decode($link['label']) }}
                </a>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- CONTACT --}}
    @if($edition->sponsoring_contact_phone || $edition->sponsoring_contact_email)
    <div class="sp-section sp-contact">
        <h3><span class="sp-icon"> 
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92V21a2 2 0 01-2 2 19 19 0 01-8.63-2.56A19 19 0 013.3 8.63 19 19 0 016.58 3 2 2 0 018.6 1h3.09a2 2 0 012 1.72c.12.86.54 2.24.9 3.3a2 2 0 01-.45 2.11L12.7 10.7"/></svg>
        </span> Nous contacter</h3>
        <div class="sp-contact-items">
            @if($edition->sponsoring_contact_phone)
                <div class="sp-contact-item"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92V21a2 2 0 01-2 2 19 19 0 01-8.63-2.56A19 19 0 013.3 8.63 19 19 0 016.58 3 2 2 0 018.6 1h3.09a2 2 0 012 1.72c.12.86.54 2.24.9 3.3a2 2 0 01-.45 2.11L12.7 10.7"/></svg>
                </span> {{ e($edition->sponsoring_contact_phone) }}</div>
            @endif
            @if($edition->sponsoring_contact_email)
                <div class="sp-contact-item"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 8l9 6 9-6"/><path d="M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8"/></svg>
                </span> <a href="mailto:{{ e($edition->sponsoring_contact_email) }}">{{ e($edition->sponsoring_contact_email) }}</a></div>
            @endif
        </div>
    </div>
    @endif

</div>

@endif

@endsection
