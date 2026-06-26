@extends('layouts.app')

@section('title', 'Investir dans ' . $project->title)

@push('styles')
<style>
    .inv-invest-container { width: min(100%, 600px); margin: 40px auto; }
    .inv-invest-back { display: inline-block; margin-bottom: 16px; color: #E8490F; text-decoration: none; font-weight: 600; }
    .inv-invest-back:hover { text-decoration: underline; }
    .inv-invest-box { background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; padding: 24px; }
    .inv-invest-h1 { margin: 0 0 8px; font-size: 1.6rem; color: #333; }
    .inv-invest-subtitle { color: #5d6678; margin-bottom: 24px; }
    .inv-invest-group { margin-bottom: 16px; }
    .inv-invest-label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.9rem; color: #333; }
    .inv-invest-input, .inv-invest-textarea { width: 100%; padding: 10px; border: 1px solid #dfe3ea; border-radius: 4px; font-size: 0.95rem; font-family: Arial, sans-serif; color: #333; }
    .inv-invest-input:focus, .inv-invest-textarea:focus { outline: none; border-color: #E8490F; box-shadow: 0 0 0 3px rgba(232, 73, 15, 0.1); }
    .inv-invest-textarea { resize: vertical; min-height: 120px; }
    .inv-invest-error { color: #dc2626; font-size: 0.85rem; margin-top: 4px; }
    .inv-invest-group.error .inv-invest-input, .inv-invest-group.error .inv-invest-textarea { border-color: #dc2626; }
    .inv-invest-btn { display: inline-flex; width: 100%; align-items: center; justify-content: center; min-height: 44px; background: #E8490F; color: #fff; border: none; border-radius: 6px; font-size: 0.95rem; font-weight: 700; cursor: pointer; margin-top: 12px; }
    .inv-invest-btn:hover { background: #C73D0A; }
    .inv-invest-btn:disabled { background: #ccc; cursor: not-allowed; }
</style>
@endpush

@section('content')
<div class="inv-invest-container">
    <a href="{{ route('projects.show', ['project' => $project->slug]) }}" class="inv-invest-back">← Retour au projet</a>

    <div class="inv-invest-box animate-left">
        <h1 class="inv-invest-h1">Investir dans ce projet</h1>
        <p class="inv-invest-subtitle">{{ $project->title }}</p>

        <form method="POST" action="{{ route('projects.invest', ['project' => $project->slug]) }}">
            @csrf

            <div class="inv-invest-group animate-left @if ($errors->has('intended_amount') && session('_old_input')) error @endif">
                <label for="intended_amount" class="inv-invest-label">Montant proposé (F CFA) *</label>
                <input type="number" name="intended_amount" id="intended_amount" class="inv-invest-input" value="{{ old('intended_amount') }}" step="1" min="1" required>
                @if ($errors->has('intended_amount') && session('_old_input'))
                    <div class="inv-invest-error">{{ $errors->first('intended_amount') }}</div>
                @endif
            </div>

            <div class="inv-invest-group animate-left @if ($errors->has('message') && session('_old_input')) error @endif">
                <label for="message" class="inv-invest-label">Message (optionnel)</label>
                <textarea name="message" id="message" class="inv-invest-textarea" placeholder="Partagez vos motivations ou vos conditions...">{{ old('message') }}</textarea>
                @if ($errors->has('message') && session('_old_input'))
                    <div class="inv-invest-error">{{ $errors->first('message') }}</div>
                @endif
            </div>

            <button
                type="submit"
                id="submit-btn"
                class="inv-invest-btn"
                onclick="this.disabled=true;
                         this.innerText='Envoi en cours...';
                         this.form.submit();">
                Soumettre ma proposition
            </button>
        </form>
    </div>
</div>
@endsection

