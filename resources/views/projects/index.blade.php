@extends('layouts.app')

@section('title', 'Projets à financer')

@push('styles')
<style>
    .proj-header { margin-bottom: 24px; }
    .proj-eyebrow { color: #E8490F; font-size: .82rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
    .proj-h1 { margin: 6px 0 10px; font-size: clamp(1.9rem, 5vw, 3rem); }
    .proj-lead { max-width: 680px; color: #5d6678; }
    .proj-empty, .proj-card { background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; }
    .proj-empty { padding: 22px; }
    .proj-grid { display: grid; grid-template-columns: 1fr; gap: 18px; }
    .proj-card { overflow: hidden; box-shadow: 0 1px 3px rgba(15, 23, 42, .08); }
    .proj-card img, .proj-placeholder { width: 100%; height: 210px; object-fit: cover; display: block; }
    .proj-placeholder { display: flex; align-items: center; justify-content: center; background: #d1fae5; color: #064e3b; font-weight: 700; }
    .proj-content { padding: 18px; }
    .proj-summary { color: #5d6678; font-size: .95rem; min-height: 64px; }
    .proj-amounts { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 18px; font-weight: 700; font-size: .92rem; }
    .proj-progress { height: 12px; overflow: hidden; background: #e2e8f0; border-radius: 999px; margin-top: 8px; }
    .proj-progress span { display: block; height: 100%; background: #E8490F; border-radius: 999px; }
    .proj-goal { margin: 8px 0 0; color: #667085; font-size: .82rem; }
    .proj-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
    .proj-btn { display: inline-flex; align-items: center; justify-content: center; min-height: 40px; padding: 0 15px; border-radius: 6px; font-size: .92rem; font-weight: 700; text-decoration: none; cursor: pointer; border: 1px solid transparent; }
    .proj-btn.primary { background: #E8490F; color: #fff; }
    .proj-btn.primary:hover { background: #C73D0A; }
    .proj-btn.secondary { border: 1px solid #E8490F; color: #E8490F; background: #fff; }
    .proj-btn.secondary:hover { background: #fff5f0; }
    .proj-pagination { margin-top: 24px; }
    @media (min-width: 700px) { .proj-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .proj-grid { grid-template-columns: repeat(3, 1fr); } }
</style>
@endpush

@section('content')
<header class="proj-header">
    <div class="proj-eyebrow">Projets</div>
    <h1 class="proj-h1">Projets à financer</h1>
    <p class="proj-lead">Découvrez les projets publiés et accompagnez ceux qui construisent l'avenir de Jeunesse en Jésus.</p>
</header>

@if ($projects->isEmpty())
    <section class="proj-empty">
        <h2>Aucun projet publié</h2>
        <p>Les projets à financer seront affichés ici dès leur publication.</p>
    </section>
@else
    <section class="proj-grid">
        @foreach ($projects as $project)
            @php
                $progress = $projectService->getProgressPercentage($project);
            @endphp

            <article class="proj-card animate-left">
                @if ($project->featured_image_path)
                    <img src="{{ asset('storage/' . $project->featured_image_path) }}" alt="{{ $project->title }}">
                @else
                    <div class="proj-placeholder">Projet</div>
                @endif

                <div class="proj-content">
                    <h2>{{ $project->title }}</h2>
                    <p class="proj-summary">{{ $project->summary }}</p>

                    <div class="proj-amounts">
                        <span>{{ number_format((float) $project->funded_amount, 0, ',', ' ') }} XOF</span>
                        <span>{{ number_format($progress, 2, ',', ' ') }} %</span>
                    </div>
                    <div class="proj-progress" aria-label="Progression du financement">
                        <span style="width: {{ $progress }}%"></span>
                    </div>
                    <p class="proj-goal">Collecté : {{ number_format($project->funded_amount, 0, ',', ' ') }} XOF | Objectif : {{ number_format($project->funding_goal, 0, ',', ' ') }} XOF</p>

                    <div class="proj-actions">
                        <a class="proj-btn primary" href="{{ route('projects.show', ['project' => $project->slug]) }}">Voir</a>
                        <a class="proj-btn secondary" href="{{ route('projects.invest.form', ['project' => $project->slug]) }}">Investir</a>
                    </div>
                </div>
            </article>
        @endforeach
    </section>

    <div class="proj-pagination">
        {{ $projects->links() }}
    </div>
@endif
@endsection
