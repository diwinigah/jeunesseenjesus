@extends('layouts.app')

@section('title', 'Sponsoring ' . ($edition?->name ?? 'Camp'))

@push('styles')

<style>
/* icônes inline pour remplacer les émojis */
.sp-icon { display: inline-flex; align-items: center; justify-content: center; }
.sp-section-title .sp-icon svg { width: 22px; height: 22px; color: #E8490F; }
.sp-bourse-icon .sp-icon svg { width: 36px; height: 36px; color: #E8490F; }
.sp-bourse-libre .sp-icon svg { color: #d4a017; }
.sp-payment-icon .sp-icon svg { width: 40px; height: 40px; color: #3D2B1F; }
.sp-contact-item .sp-icon svg { width: 20px; height: 20px; color: #fff; }
.sp-nature-num svg { width: 14px; height: 14px; }

/* ═══════════════════════════════════
   SPONSORING PAGE — DESIGN PRO
════════════════════════════════════ */
.sp-page { max-width: 1000px; margin: 0 auto; padding: 0 1rem 4rem; }

/* HERO */
.sp-hero { position: relative; border-radius: 20px; overflow: hidden; margin-bottom: 3rem; box-shadow: 0 8px 32px rgba(0,0,0,0.18); }
.sp-hero-img img { width: 100%; max-height: 280px; object-fit: cover; display: block; filter: brightness(0.7); }
.sp-hero-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(61,43,31,0.3), rgba(61,43,31,0.85)); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; text-align: center; }
.sp-hero-tag { background: #E8490F; color: #fff; padding: 0.3rem 1.2rem; border-radius: 30px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 1rem; }
.sp-hero-title { font-size: 2.2rem; font-weight: 900; color: #fff; margin: 0 0 0.5rem; text-shadow: 0 2px 8px rgba(0,0,0,0.4); }
.sp-hero-theme { font-size: 1.15rem; color: #f9c97c; font-style: italic; font-weight: 600; margin: 0.25rem 0; }
.sp-hero-verse { font-size: 0.9rem; color: rgba(255,255,255,0.85); margin: 0.5rem 0; }
.sp-hero-dates { font-size: 0.95rem; color: #fff; margin-top: 0.75rem; background: rgba(255,255,255,0.15); padding: 0.4rem 1rem; border-radius: 20px; backdrop-filter: blur(4px); }

/* NO HERO IMAGE fallback */
.sp-hero-no-img { background: linear-gradient(135deg, #3D2B1F 0%, #6b3a25 100%); padding: 3rem 2rem; text-align: center; border-radius: 20px; margin-bottom: 3rem; }

/* SECTIONS */
.sp-section { margin-bottom: 3rem; }
.sp-section-title { font-size: 1.1rem; font-weight: 800; color: #3D2B1F; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; padding-bottom: 0.6rem; border-bottom: 2px solid #f0e8e4; }

/* INTRO */
.sp-intro { font-size: 1rem; line-height: 1.9; color: #444; background: #fdf6f3; border-left: 4px solid #E8490F; padding: 1.5rem 1.75rem; border-radius: 0 16px 16px 0; }

/* PROGRESSION */
.sp-progress-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
.sp-progress-card { background: #fff; border: 1px solid #f0e8e4; border-radius: 16px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
.sp-progress-top { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.75rem; }
.sp-progress-label { font-size: 0.88rem; color: #666; font-weight: 600; }
.sp-progress-value { font-size: 0.95rem; font-weight: 800; color: #3D2B1F; }
.sp-progress-track { background: #f0e8e4; border-radius: 99px; height: 14px; overflow: hidden; }
.sp-progress-fill { background: linear-gradient(90deg, #E8490F, #ff6b35); height: 100%; border-radius: 99px; transition: width 1s ease; }
.sp-progress-fill-green { background: linear-gradient(90deg, #16a34a, #22c55e); }
.sp-progress-pct { font-size: 0.82rem; color: #E8490F; font-weight: 700; text-align: right; margin-top: 0.4rem; display: block; }

/* BOURSES */
.sp-bourse-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
.sp-bourse-card { background: #fff; border: 2px solid #f0e8e4; border-radius: 16px; padding: 1.5rem 1rem; text-align: center; transition: transform 0.2s, box-shadow 0.2s; }
.sp-bourse-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(232,73,15,0.12); }
.sp-bourse-featured { border-color: #E8490F; background: linear-gradient(135deg, #fff8f6, #fff); grid-column: span 1; }
.sp-bourse-libre { border-color: #f9c97c; background: linear-gradient(135deg, #fffdf5, #fff); }
.sp-bourse-icon { font-size: 2rem; margin-bottom: 0.6rem; }
.sp-bourse-card h4 { font-size: 0.95rem; font-weight: 800; color: #3D2B1F; margin-bottom: 0.4rem; }
.sp-bourse-card p { font-size: 0.78rem; color: #888; margin-bottom: 0.6rem; line-height: 1.4; }
.sp-bourse-amount { font-size: 1.5rem; font-weight: 900; color: #E8490F; }
.sp-bourse-amount small { font-size: 0.7rem; font-weight: 600; color: #aaa; display: block; margin-top: 0.2rem; }
.sp-bourse-libre .sp-bourse-amount { color: #d4a017; }

/* RÉPARTITION */
.sp-repartition { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
.sp-rep-card { background: #fff; border: 1px solid #f0e8e4; border-radius: 16px; padding: 1.25rem; }
.sp-rep-card h4 { font-size: 0.88rem; font-weight: 700; color: #3D2B1F; margin-bottom: 1rem; }
.sp-rep-row { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #f9f3f0; font-size: 0.88rem; }
.sp-rep-row:last-child { border-bottom: none; }
.sp-rep-row span { color: #555; }
.sp-rep-row strong { color: #3D2B1F; font-weight: 700; }
.sp-rep-total { background: #fdf6f3; border-radius: 8px; padding: 0.6rem 0.75rem; margin-top: 0.5rem; display: flex; justify-content: space-between; font-weight: 800; color: #E8490F; font-size: 0.95rem; }

/* BUDGET TABLE */
.sp-budget-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); font-size: 0.88rem; }
.sp-budget-table thead tr { background: #3D2B1F; color: #fff; }
.sp-budget-table th { padding: 0.75rem 1rem; text-align: left; font-weight: 700; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.04em; }
.sp-budget-table td { padding: 0.65rem 1rem; border-bottom: 1px solid #f0e8e4; color: #444; }
.sp-budget-table tbody tr:hover { background: #fdf6f3; }
.sp-budget-table tbody tr:last-child td { border-bottom: none; }
.sp-budget-total-row td { background: #fdf6f3; font-weight: 800; color: #3D2B1F; border-top: 2px solid #E8490F; }
.sp-budget-amount { text-align: right; font-weight: 600; color: #3D2B1F; }
.sp-budget-total-amount { color: #E8490F; font-size: 1rem; }

/* NATURE */
.sp-nature-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
.sp-nature-card { display: flex; align-items: flex-start; gap: 0.85rem; background: #fdf6f3; border-radius: 12px; padding: 1rem 1.1rem; border: 1px solid #f0e8e4; }
.sp-nature-num { background: #E8490F; color: #fff; border-radius: 50%; min-width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 800; flex-shrink: 0; }
.sp-nature-card p { font-size: 0.875rem; color: #444; margin: 0; line-height: 1.55; }

/* PAIEMENT */
.sp-payment-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
.sp-payment-card { background: #fff; border: 1px solid #f0e8e4; border-radius: 16px; padding: 1.5rem 1rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
.sp-payment-icon { font-size: 2.2rem; margin-bottom: 0.6rem; }
.sp-payment-card h4 { font-weight: 800; color: #3D2B1F; margin-bottom: 0.5rem; font-size: 0.9rem; }
.sp-payment-card p { font-size: 0.82rem; color: #555; margin: 0.2rem 0; line-height: 1.5; }
.sp-payment-btn { display: inline-block; margin-top: 0.75rem; background: #E8490F; color: #fff; padding: 0.5rem 1.1rem; border-radius: 25px; font-size: 0.82rem; font-weight: 700; text-decoration: none; transition: background 0.2s; }
.sp-payment-btn:hover { background: #c73d0d; color: #fff; }

/* CONTACT */
.sp-contact { background: linear-gradient(135deg, #3D2B1F, #6b3a25); border-radius: 20px; padding: 2.5rem; text-align: center; color: #fff; }
.sp-contact h3 { color: #fff; font-size: 1.2rem; font-weight: 800; margin-bottom: 1.25rem; }
.sp-contact-items { display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; }
.sp-contact-item { display: flex; align-items: center; gap: 0.6rem; font-size: 0.95rem; font-weight: 600; color: #fff; }
.sp-contact-item span { font-size: 1.2rem; }

/* CLOSED */
.sp-closed { text-align: center; padding: 5rem 1rem; }
.sp-closed-icon { font-size: 4rem; margin-bottom: 1rem; }
.sp-closed h1 { color: #3D2B1F; font-size: 1.5rem; margin-bottom: 0.75rem; }
.sp-closed p { color: #888; }

/* RESPONSIVE */
@media (max-width: 768px) {
    .sp-progress-grid { grid-template-columns: 1fr; }
    .sp-bourse-grid { grid-template-columns: repeat(2, 1fr); }
    .sp-repartition { grid-template-columns: 1fr; }
    .sp-nature-grid { grid-template-columns: 1fr; }
    .sp-payment-grid { grid-template-columns: repeat(2, 1fr); }
    .sp-hero-title { font-size: 1.5rem; }
}
@media (max-width: 480px) {
    .sp-bourse-grid { grid-template-columns: 1fr 1fr; }
    .sp-payment-grid { grid-template-columns: 1fr 1fr; }
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
                </span> {{ \Carbon\Carbon::parse($edition->camp_start_date)->translatedFormat('d F') }} — {{ \Carbon\Carbon::parse($edition->camp_end_date)->translatedFormat('d F Y') }}</div>
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

        <div class="sp-bourse-card sp-bourse-featured">
            <div class="sp-bourse-icon"><span class="sp-icon"> 
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.153c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.95c.3.921-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.176 0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.287-3.95a1 1 0 00-.364-1.118L2.07 9.377c-.783-.57-.38-1.81.588-1.81h4.153a1 1 0 00.95-.69l1.286-3.95z"/></svg>
            </span></div>
            <h4>Bourse Pleine</h4>
            <p>Couvrez l'intégralité des frais d'un jeune</p>
            <div class="sp-bourse-amount">{{ number_format($edition->bourse_pleine_amount ?? 0, 0, ',', ' ') }} FCFA <small>par campeur</small></div>
        </div>
    </div>

    {{-- BOURSES DÉTAILLÉES --}}
    <div class="sp-section">
        <div class="sp-section-title"><span class="sp-icon"> 
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422"/><path d="M12 14v7"/></svg>
        </span> Types de bourses</div>
        <div class="sp-bourse-grid">
            @if($edition->bourse_adulte_amount)
            <div class="sp-bourse-card">
                <div class="sp-bourse-icon"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 00-3-3.87"/><path d="M4 21v-2a4 4 0 013-3.87"/><circle cx="12" cy="7" r="4"/></svg>
                </span></div>
                <h4>Adulte</h4>
                <div class="sp-bourse-amount">{{ number_format($edition->bourse_adulte_amount,0,',',' ') }} FCFA</div>
            </div>
            @endif

            @if($edition->bourse_etudiant_amount)
            <div class="sp-bourse-card">
                <div class="sp-bourse-icon"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422"/><path d="M12 14v7"/></svg>
                </span></div>
                <h4>Étudiant</h4>
                <div class="sp-bourse-amount">{{ number_format($edition->bourse_etudiant_amount,0,',',' ') }} FCFA</div>
            </div>
            @endif

            @if($edition->bourse_lycee_amount)
            <div class="sp-bourse-card">
                <div class="sp-bourse-icon"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20l9-5-9-5-9 5 9 5z"/><path d="M12 12l9-5"/></svg>
                </span></div>
                <h4>Lycée / Collège</h4>
                <div class="sp-bourse-amount">{{ number_format($edition->bourse_lycee_amount,0,',',' ') }} FCFA</div>
            </div>
            @endif

            @if($edition->bourse_enfant_amount)
            <div class="sp-bourse-card">
                <div class="sp-bourse-icon"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 00-3-3.87"/><path d="M4 21v-2a4 4 0 013-3.87"/><circle cx="12" cy="7" r="4"/></svg>
                </span></div>
                <h4>Enfant</h4>
                <div class="sp-bourse-amount">{{ number_format($edition->bourse_enfant_amount,0,',',' ') }} FCFA</div>
            </div>
            @endif

            <div class="sp-bourse-card sp-bourse-libre">
                <div class="sp-bourse-icon"><span class="sp-icon"> 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21s-8-4.35-8-10a5 5 0 0110 0 5 5 0 0110 0c0 5.65-8 10-8 10z"/></svg>
                </span></div>
                <h4>Bourse Partielle</h4>
                <p>Contribuez selon votre cœur</p>
                <div class="sp-bourse-amount">Libre</div>
            </div>
        </div>
    </div>

    {{-- RÉPARTITION PARTICIPANTS --}}
    @php
        $totalParticipants = ($edition->participants_adultes ?? 0) + ($edition->participants_etudiants ?? 0) + ($edition->participants_lycee ?? 0) + ($edition->participants_enfants ?? 0);
    @endphp
    @if($totalParticipants > 0 || ($edition->participants_geo && count($edition->participants_geo) > 0))
    <div class="sp-section">
        <div class="sp-section-title"><span class="sp-icon"> 
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 00-3-3.87"/><path d="M7 21v-2a4 4 0 013-3.87"/><circle cx="12" cy="7" r="4"/></svg>
        </span> Répartition des participants</div>
        <div class="sp-repartition">
            <div class="sp-rep-card">
                <h4>Par catégorie</h4>
                @if(($edition->participants_adultes ?? 0) > 0)
                    <div class="sp-rep-row"><span>Adultes</span><strong>{{ $edition->participants_adultes }}</strong></div>
                @endif
                @if(($edition->participants_etudiants ?? 0) > 0)
                    <div class="sp-rep-row"><span>Étudiants</span><strong>{{ $edition->participants_etudiants }}</strong></div>
                @endif
                @if(($edition->participants_lycee ?? 0) > 0)
                    <div class="sp-rep-row"><span>Lycée / Collège</span><strong>{{ $edition->participants_lycee }}</strong></div>
                @endif
                @if(($edition->participants_enfants ?? 0) > 0)
                    <div class="sp-rep-row"><span>Enfants</span><strong>{{ $edition->participants_enfants }}</strong></div>
                @endif
                <div class="sp-rep-total"><span>Total</span><strong>{{ $totalParticipants }}</strong></div>
            </div>

            <div class="sp-rep-card">
                <h4>Par ville / zone</h4>
                @if($edition->participants_geo && count($edition->participants_geo) > 0)
                    @foreach($edition->participants_geo as $geo)
                        <div class="sp-rep-row"><span>{{ $geo['ville'] ?? '—' }}</span><strong>{{ $geo['nombre'] ?? 0 }}</strong></div>
                    @endforeach
                    @php $geoTotal = collect($edition->participants_geo)->sum('nombre'); @endphp
                    <div class="sp-rep-total"><span>Total</span><strong>{{ $geoTotal }}</strong></div>
                @else
                    <p>Aucune répartition géographique renseignée.</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- BUDGET PRÉVISIONNEL --}}
    @if($edition->budget_expenses && count($edition->budget_expenses) > 0)
    @php
        $totalDepenses = collect($edition->budget_expenses)->sum(fn($e) => ($e['prix_unitaire'] ?? 0) * ($e['quantite'] ?? 1));
    @endphp
        <div class="sp-section">
        <div class="sp-section-title"><span class="sp-icon"> 
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 12h6M9 16h6M7 8h10"/><rect x="3" y="4" width="18" height="16" rx="2"/></svg>
        </span> Budget prévisionnel</div>
        <table class="sp-budget-table">
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th>Prix unitaire</th>
                    <th>Qté</th>
                    <th class="sp-budget-amount">Montant (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($edition->budget_expenses as $expense)
                @php $montant = ($expense['prix_unitaire'] ?? 0) * ($expense['quantite'] ?? 1); @endphp
                <tr>
                    <td>{{ $expense['designation'] ?? '—' }}</td>
                    <td>{{ number_format($expense['prix_unitaire'] ?? 0, 0, ',', ' ') }}</td>
                    <td>{{ $expense['quantite'] ?? 1 }}</td>
                    <td class="sp-budget-amount">{{ number_format($montant, 0, ',', ' ') }}</td>
                </tr>
                @endforeach
                <tr class="sp-budget-total-row">
                    <td colspan="3">TOTAL</td>
                    <td class="sp-budget-total-amount">{{ number_format($totalDepenses, 0, ',', ' ') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    {{-- APPORTS EN NATURE --}}
    @if($edition->nature_contributions && count($edition->nature_contributions) > 0)
        <div class="sp-section">
        <div class="sp-section-title"><span class="sp-icon"> 
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7l9-4 9 4"/><path d="M21 7v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7"/></svg>
        </span> Apports en nature</div>
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
        </span> Comment contribuer ?</div>
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
