@extends('layouts.app')

@section('title', 'Inscription envoyée')

@push('styles')
<style>
    .success-container { text-align: center; }
    .success-box { background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; padding: 40px 28px; margin: 0 auto; max-width: 560px; }
    .success-box h1 { margin: 0 0 16px; font-size: clamp(1.7rem, 5vw, 2.3rem); }
    .success-box p { line-height: 1.6; color: #475467; margin: 12px 0; }
    .success-ref { display: inline-block; color: #E8490F; font-weight: 700; }
</style>
@endpush

@section('content')
<div class="success-container">
    <div class="success-box">
        <h1>Inscription reçue</h1>
        <p>Merci. Votre demande a bien été envoyée.</p>
        <p>Numéro d'inscription : <span class="success-ref">{{ $registration }}</span></p>
    </div>
</div>
@endsection
