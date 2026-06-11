@extends('layouts.app')

@section('title', $project->title)

@push('styles')
<style>
    .proj-show-container { width: min(100%, 980px); margin: 40px auto; }
    .proj-show-back { display: inline-block; margin-bottom: 16px; color: #E8490F; font-weight: 700; text-decoration: none; }
    .proj-show-back:hover { text-decoration: underline; }
    .proj-show-article { margin-top: 18px; overflow: hidden; background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; box-shadow: 0 1px 3px rgba(15, 23, 42, .08); }
    .proj-show-img, .proj-show-placeholder { width: 100%; max-height: 520px; object-fit: cover; display: block; }
    .proj-show-placeholder { height: 320px; display: flex; align-items: center; justify-content: center; background: #d1fae5; color: #064e3b; font-size: 1.2rem; font-weight: 700; }
    .proj-show-content { padding: 22px; }
    .proj-show-h1 { margin: 0; font-size: clamp(2rem, 5vw, 3.2rem); color: #333; }
    .proj-show-funding { margin-top: 24px; padding: 18px; border: 1px solid #dfe3ea; border-radius: 8px; background: #fef8f3; }
    .proj-show-funding-top { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 16px; }
    .proj-show-label { margin: 0 0 6px; color: #667085; font-size: .9rem; }
    .proj-show-collected { margin: 0; font-size: 1.6rem; font-weight: 800; color: #E8490F; }
    .proj-show-goal { margin: 0; font-size: 1.2rem; font-weight: 700; color: #333; }
    .proj-show-progress { height: 14px; overflow: hidden; background: #e2e8f0; border-radius: 999px; margin-top: 18px; }
    .proj-show-progress span { display: block; height: 100%; background: #E8490F; border-radius: 999px; }
    .proj-show-percentage { margin: 8px 0 0; color: #E8490F; font-size: .95rem; font-weight: 700; }
    .proj-show-description { margin-top: 28px; line-height: 1.6; color: #333; }
    .proj-show-description img { max-width: 100%; height: auto; }
    .proj-show-invest { display: inline-flex; align-items: center; justify-content: center; min-height: 44px; margin-top: 26px; padding: 0 18px; border-radius: 6px; background: #E8490F; color: #fff; font-size: .95rem; font-weight: 700; text-decoration: none; cursor: pointer; }
    .proj-show-invest:hover { background: #C73D0A; }
    @media (min-width: 760px) { .proj-show-content { padding: 32px; } }
</style>
@endpush

@section('content')
@php
    $progress = $projectService->getProgressPercentage($project);
@endphp

<div class="proj-show-container">
    <a href="{{ route('projects.index') }}" class="proj-show-back">← Retour aux projets</a>

    <article class="proj-show-article">
        @if ($project->featured_image_path)
            <img src="{{ asset('storage/' . $project->featured_image_path) }}" alt="{{ $project->title }}" class="proj-show-img">
        @else
            <div class="proj-show-placeholder">Projet</div>
        @endif

        <div class="proj-show-content">
            <h1 class="proj-show-h1">{{ $project->title }}</h1>

            <section class="proj-show-funding">
                <div class="proj-show-funding-top">
                    <div>
                        <p class="proj-show-label">Montant collecté</p>
                        <p class="proj-show-collected">{{ number_format((float) $project->funded_amount, 0, ',', ' ') }} XOF</p>
                    </div>
                    <div>
                        <p class="proj-show-label">Objectif</p>
                        <p class="proj-show-goal">{{ number_format((float) $project->funding_goal, 0, ',', ' ') }} XOF</p>
                    </div>
                </div>

                <div class="proj-show-progress" aria-label="Progression du financement">
                    <span style="width: {{ $progress }}%"></span>
                </div>
                <p class="proj-show-percentage">{{ number_format($progress, 2, ',', ' ') }} % financé</p>
                <p class="proj-show-label">Collecté : {{ number_format($project->funded_amount, 0, ',', ' ') }} XOF | Objectif : {{ number_format($project->funding_goal, 0, ',', ' ') }} XOF</p>
            </section>

            <div class="proj-show-description">
                {!! $project->description !!}
            </div>

            <a class="proj-show-invest" href="{{ route('projects.invest.form', ['project' => $project->slug]) }}">Investir</a>
        </div>
    </article>
</div>
@endsection
</body>
</html>
