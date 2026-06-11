@extends('layouts.app')

@section('title', 'Partenaires')

@push('styles')
<style>
    .part-idx-header { margin-bottom: 24px; }
    .part-idx-eyebrow { color: #E8490F; font-size: .82rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
    .part-idx-h1 { margin: 6px 0 10px; font-size: clamp(1.9rem, 5vw, 3rem); color: #333; }
    .part-idx-lead { max-width: 680px; color: #5d6678; }
    .part-idx-empty, .part-idx-card { background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; }
    .part-idx-empty { padding: 22px; }
    .part-idx-grid { display: grid; grid-template-columns: 1fr; gap: 18px; }
    .part-idx-card { overflow: hidden; box-shadow: 0 1px 3px rgba(15, 23, 42, .08); }
    .part-idx-logo-wrapper { width: 100%; height: 160px; display: flex; align-items: center; justify-content: center; background: #f3f4f6; border-bottom: 1px solid #dfe3ea; }
    .part-idx-logo { max-width: 100%; max-height: 160px; object-fit: contain; display: block; }
    .part-idx-initials { width: 80px; height: 80px; border-radius: 50%; background: #E8490F; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.5rem; }
    .part-idx-content { padding: 18px; }
    .part-idx-name { font-weight: 700; font-size: 1.05rem; margin: 0 0 8px; color: #333; }
    .part-idx-type { display: inline-block; padding: 4px 8px; background: #fef2e8; color: #c73d0a; border-radius: 4px; font-size: .82rem; font-weight: 600; margin-bottom: 12px; }
    .part-idx-desc { color: #5d6678; font-size: .95rem; min-height: 48px; margin-bottom: 12px; }
    .part-idx-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
    .part-idx-btn { display: inline-flex; align-items: center; justify-content: center; min-height: 40px; padding: 0 15px; border-radius: 6px; font-size: .92rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; }
    .part-idx-btn.primary { background: #E8490F; color: #fff; }
    .part-idx-btn.secondary { border: 1px solid #E8490F; color: #E8490F; background: #fff; }
    .part-idx-btn:hover { opacity: 0.85; }
    .part-idx-cta { text-align: center; margin-top: 32px; padding: 24px; background: #fef2e8; border: 1px solid #fecaca; border-radius: 8px; }
    .part-idx-cta h2 { font-size: 1.3rem; margin-bottom: 12px; color: #333; }
    .part-idx-cta p { color: #5d6678; margin-bottom: 16px; }
    @media (min-width: 700px) { .part-idx-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .part-idx-grid { grid-template-columns: repeat(3, 1fr); } }
</style>
@endpush

@section('content')
<div class="part-idx-header">
    <div class="part-idx-eyebrow">Jeunesse en Jésus</div>
    <h1 class="part-idx-h1">Nos partenaires</h1>
    <p class="part-idx-lead">Découvrez les organisations et entreprises qui soutiennent nos initiatives et accompagnent notre mission.</p>
</div>

@if ($partners->isEmpty())
    <section class="part-idx-empty">
        <h2>Aucun partenaire publié</h2>
        <p>Revenez bientôt pour découvrir nos partenaires.</p>
    </section>
@else
    <section class="part-idx-grid">
        @foreach ($partners as $partner)
            <article class="part-idx-card">
                <div class="part-idx-logo-wrapper">
                    @if ($partner->logo_path)
                        <img src="{{ asset('storage/' . $partner->logo_path) }}" alt="{{ $partner->name }}" class="part-idx-logo">
                    @else
                        <div class="part-idx-initials">
                            {{ substr($partner->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="part-idx-content">
                    <h3 class="part-idx-name">{{ $partner->name }}</h3>
                    @if ($partner->type)
                        <div class="part-idx-type">{{ $partner->type->label() }}</div>
                    @endif
                    @if ($partner->description)
                        <p class="part-idx-desc">{{ $partner->description }}</p>
                    @endif
                    <div class="part-idx-actions">
                        @if ($partner->website_url)
                            <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="part-idx-btn secondary">
                                Visiter le site
                            </a>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </section>
@endif

<section class="part-idx-cta">
    <h2>Vous souhaitez devenir partenaire ?</h2>
    <p>Rejoignez notre réseau de partenaires et participez à notre mission auprès de la jeunesse.</p>
    <a href="{{ route('partners.request') }}" class="part-idx-btn primary">Demander à devenir partenaire</a>
</section>
@endsection
