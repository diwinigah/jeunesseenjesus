@extends('layouts.app')

@section('title', 'Tableau de bord - Investisseur')

@push('styles')
<style>
    .inv-dash-h1 { margin: 0 0 24px; font-size: 1.8rem; color: #333; }
    .inv-dash-empty { background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; padding: 22px; text-align: center; }
    .inv-dash-table-box { background: #fff; border: 1px solid #dfe3ea; border-radius: 8px; overflow-x: auto; }
    .inv-dash-table { width: 100%; border-collapse: collapse; }
    .inv-dash-table th { background: #f3f4f6; padding: 12px 16px; text-align: left; font-weight: 700; font-size: 0.9rem; border-bottom: 1px solid #dfe3ea; }
    .inv-dash-table td { padding: 12px 16px; border-bottom: 1px solid #dfe3ea; font-size: 0.95rem; color: #333; }
    .inv-dash-table tr:last-child td { border-bottom: none; }
    .inv-dash-badge { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 700; }
    .inv-dash-badge-new { background: #dbeafe; color: #1e40af; }
    .inv-dash-badge-contacted { background: #fef3c7; color: #92400e; }
    .inv-dash-badge-pledged { background: #d1fae5; color: #065f46; }
    .inv-dash-badge-paid { background: #d1fae5; color: #065f46; }
    .inv-dash-badge-cancelled { background: #fee2e2; color: #7f1d1d; }
    .inv-dash-actions { margin-top: 20px; }
    .inv-dash-btn { display: inline-flex; align-items: center; justify-content: center; min-height: 40px; padding: 0 16px; border-radius: 6px; font-size: 0.9rem; font-weight: 700; text-decoration: none; background: #E8490F; color: #fff; border: none; cursor: pointer; }
    .inv-dash-btn:hover { background: #C73D0A; }
    .inv-dash-success { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; }
    @media (max-width: 600px) { .inv-dash-table { font-size: 0.85rem; } .inv-dash-table th, .inv-dash-table td { padding: 8px 12px; } }
</style>
@endpush

@section('content')
<div style="width: min(100%, 1000px); margin: 0 auto;">
    <h1 class="inv-dash-h1">Mes investissements</h1>

    @if (session('success'))
        <div class="inv-dash-success">{{ session('success') }}</div>
    @endif

    @if ($investments->isEmpty())
        <div class="inv-dash-empty">
            <p>Vous n'avez pas encore exprimé d'intérêt d'investissement.</p>
            <a href="{{ route('projects.index') }}" class="inv-dash-btn" style="margin-top: 12px;">Découvrir les projets</a>
        </div>
    @else
        <div class="inv-dash-table-box">
            <table class="inv-dash-table">
                <thead>
                    <tr>
                        <th>Projet</th>
                        <th>Montant proposé</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($investments as $investment)
                        <tr>
                            <td>
                                <strong>{{ $investment->project->title }}</strong>
                            </td>
                            <td>
                                {{ number_format((float) $investment->intended_amount, 0, ',', ' ') }} XOF
                            </td>
                            <td>
                                <span class="inv-dash-badge inv-dash-badge-{{ $investment->status->value }}">
                                    @switch($investment->status->value)
                                        @case('new')
                                            Nouvelle
                                            @break
                                        @case('contacted')
                                            Contacté
                                            @break
                                        @case('pledged')
                                            Engagé
                                            @break
                                        @case('paid')
                                            Payé
                                            @break
                                        @case('cancelled')
                                            Annulé
                                            @break
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                {{ $investment->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="inv-dash-actions">
            <a href="{{ route('projects.index') }}" class="inv-dash-btn">Voir d'autres projets</a>
        </div>
    @endif
</div>
@endsection
</body>
</html>
