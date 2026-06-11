@extends('layouts.app')

@section('title', 'Inscriptions fermées')

@push('styles')
<style>
    .closed-container { text-align: center; }
    .closed-box { background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; padding: 40px 28px; margin: 0 auto; max-width: 560px; }
    .closed-box h1 { margin: 0 0 16px; font-size: clamp(1.7rem, 5vw, 2.3rem); }
    .closed-box p { margin: 0; line-height: 1.6; color: #475467; }
</style>
@endpush

@section('content')
<div class="closed-container">
    <div class="closed-box">
        <h1>Inscriptions fermées</h1>
        <p>Le formulaire d'inscription événement n'est pas disponible pour le moment.</p>
    </div>
</div>
@endsection
