@extends('layouts.app')

@section('title', $edition->name . ' — Sponsoring')

@section('content')
<div class="j2-sponsoring">
    <h1>{{ $edition->sponsoring_theme ?? 'Soutenir le camp' }}</h1>

    @if($edition->sponsoring_verse)
        <p class="j2-verse">{{ $edition->sponsoring_verse }}</p>
    @endif

    @if($edition->sponsoring_intro)
        <div class="j2-intro">{!! nl2br(e($edition->sponsoring_intro)) !!}</div>
    @endif

    <section class="j2-budget">
        <h2>Objectif financier</h2>
        <p>Budget total : {{ number_format($edition->budget_total) }} {{ $edition->currency }}</p>
        <p>Montant collecté : {{ number_format($edition->budget_collected) }} {{ $edition->currency }}</p>
        <p>Participants visés : {{ $edition->participants_target }}</p>
        <p>Participants sponsorisés : {{ $edition->participants_sponsored }}</p>
    </section>

    <section class="j2-bourses">
        <h2>Bourses</h2>
        <ul>
            <li>Pleine : {{ number_format($edition->bourse_pleine_amount) }} {{ $edition->currency }}</li>
            <li>Adulte : {{ number_format($edition->bourse_adulte_amount) }} {{ $edition->currency }}</li>
            <li>Étudiant : {{ number_format($edition->bourse_etudiant_amount) }} {{ $edition->currency }}</li>
            <li>Lycée : {{ number_format($edition->bourse_lycee_amount) }} {{ $edition->currency }}</li>
            <li>Enfant : {{ number_format($edition->bourse_enfant_amount) }} {{ $edition->currency }}</li>
        </ul>
    </section>

    <section class="j2-payments">
        <h2>Moyens de paiement</h2>
        <ul>
            @if($edition->payment_flooz)
                <li>Flooz : {{ e($edition->payment_flooz) }}</li>
            @endif
            @if($edition->payment_mixx)
                <li>Mixx : {{ e($edition->payment_mixx) }}</li>
            @endif
            @if($edition->payment_iban)
                <li>IBAN : {{ e($edition->payment_iban) }}</li>
            @endif
            @if($edition->payment_paypal)
                <li>PayPal : {{ e($edition->payment_paypal) }}</li>
            @endif
            @if($edition->payment_account_name)
                <li>Nom du compte : {{ e($edition->payment_account_name) }}</li>
            @endif
            @if($edition->payment_account_number)
                <li>Numero de compte : {{ e($edition->payment_account_number) }}</li>
            @endif
        </ul>
    </section>

    <section class="j2-contact">
        <h2>Contact</h2>
        @if($edition->sponsoring_contact_email)
            <p>Email : <a href="mailto:{{ e($edition->sponsoring_contact_email) }}">{{ e($edition->sponsoring_contact_email) }}</a></p>
        @endif
        {{-- Ne jamais afficher les numéros personnels des inscrits --}}
    </section>

    @if(! empty($edition->nature_contributions) && is_array($edition->nature_contributions))
        <section class="j2-nature">
            <h2>Apports en nature</h2>
            <ul>
                @foreach($edition->nature_contributions as $item)
                    <li>{{ e($item) }}</li>
                @endforeach
            </ul>
        </section>
    @endif
</div>
@endsection
