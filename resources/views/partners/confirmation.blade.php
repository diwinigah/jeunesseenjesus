@extends('layouts.app')

@section('title', 'Confirmation - Demande de partenariat')

@push('styles')
<style>
    .part-conf-container { width: min(100%, 600px); margin: 40px auto; }
    .part-conf-box { background: #dcfce7; border: 1px solid #86efac; border-radius: 8px; padding: 32px 24px; text-align: center; }
    .part-conf-icon { font-size: 3rem; margin-bottom: 16px; }
    .part-conf-h1 { margin: 0 0 12px; font-size: 1.8rem; color: #166534; }
    .part-conf-text { line-height: 1.55; color: #5d6678; margin: 12px 0; }
    .part-conf-highlight { color: #166534; font-weight: 700; }
    .part-conf-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 24px; justify-content: center; }
    .part-conf-btn { display: inline-flex; align-items: center; justify-content: center; min-height: 44px; padding: 0 24px; border: none; border-radius: 6px; font-size: .95rem; font-weight: 700; text-decoration: none; cursor: pointer; }
    .part-conf-btn.primary { background: #E8490F; color: #fff; }
    .part-conf-btn.primary:hover { background: #C73D0A; }
    .part-conf-btn.secondary { background: #fff; color: #333; border: 1px solid #dfe3ea; }
    .part-conf-btn.secondary:hover { background: #f9fafb; }
</style>
@endpush

@section('content')
<div class="part-conf-container">
    <section class="part-conf-box">
        <div class="part-conf-icon">✓</div>
        <h1 class="part-conf-h1">Demande reçue !</h1>
        <p class="part-conf-text">Merci de votre intérêt pour devenir partenaire de <span class="part-conf-highlight">Jeunesse en Jésus</span>.</p>
        <p class="part-conf-text">Votre demande a été enregistrée avec succès. Notre équipe examinera votre demande et vous <span class="part-conf-highlight">contactera bientôt</span> aux coordonnées que vous avez fournies.</p>
        <p class="part-conf-text">En attendant, n'hésitez pas à explorer nos projets et notre communauté.</p>

        <div class="part-conf-actions">
            <a href="{{ route('partners.index') }}" class="part-conf-btn primary">
                Retour aux partenaires
            </a>
            <a href="{{ route('projects.index') }}" class="part-conf-btn secondary">
                Découvrir nos projets
            </a>
        </div>
    </section>
</div>
@endsection
