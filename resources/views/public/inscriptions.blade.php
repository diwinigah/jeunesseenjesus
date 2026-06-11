@extends('layouts.app')

@section('title', $edition->name . ' - Liste des inscrits')

@push('styles')
<style>
    .insc-container { max-width: 1200px; margin: 40px auto; padding: 0 16px; }
    .insc-header { background: white; border-radius: 8px; padding: 24px 20px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }
    .insc-h1 { font-size: clamp(1.5rem, 5vw, 2rem); margin-bottom: 8px; color: #333; }
    .insc-h1-sub { color: #667085; font-size: 0.95rem; margin: 0; }
    .insc-table-wrapper { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden; }
    .insc-table { width: 100%; border-collapse: collapse; }
    .insc-table th { background: #f3f4f6; padding: 16px 12px; text-align: left; font-weight: 600; font-size: 0.875rem; color: #374151; border-bottom: 1px solid #e5e7eb; }
    .insc-table td { padding: 16px 12px; border-bottom: 1px solid #e5e7eb; font-size: 0.9375rem; color: #333; }
    .insc-table tr:hover { background: #f9fafb; }
    .insc-table tr:last-child td { border-bottom: none; }
    .insc-badge { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 0.8125rem; font-weight: 500; white-space: nowrap; }
    .insc-badge-paid { background: #d1fae5; color: #065f46; }
    .insc-badge-partial { background: #fef3c7; color: #92400e; }
    .insc-badge-unpaid { background: #fee2e2; color: #991b1b; }
    .insc-badge-confirmed { background: #d1fae5; color: #065f46; }
    .insc-badge-pending { background: #fef3c7; color: #92400e; }
    .insc-badge-cancelled { background: #fee2e2; color: #991b1b; }
    .insc-pagination { display: flex; justify-content: center; gap: 8px; margin-top: 24px; flex-wrap: wrap; }
    .insc-pagination a, .insc-pagination span { padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; background: white; color: #E8490F; text-decoration: none; font-size: 0.875rem; transition: all 0.2s; }
    .insc-pagination a:hover { background: #E8490F; color: white; }
    .insc-pagination span.active { background: #E8490F; color: white; border-color: #E8490F; }
    .insc-pagination span:not(.active) { color: #6b7280; }
    .insc-empty { text-align: center; padding: 40px 20px; color: #6b7280; }
    .insc-hidden-sm { display: table-cell; }
    .insc-hidden-sm { display: table-cell; }
    @media (max-width: 768px) {
        .insc-hidden-sm { display: none; }
        .insc-table th, .insc-table td { padding: 12px 8px; font-size: 0.875rem; }
    }
</style>
@endpush

@section('content')
<div class="insc-container">
    <div class="insc-header">
        <h1 class="insc-h1">{{ $edition->name }} — Liste des inscrits</h1>
        <p class="insc-h1-sub">Total : {{ $registrations->total() }}</p>
    </div>

    <div class="insc-table-wrapper">
        <div style="overflow-x:auto;">
            <table class="insc-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th class="insc-hidden-sm">Ville</th>
                        <th class="insc-hidden-sm">Section</th>
                        <th>Paiement</th>
                        <th class="insc-hidden-sm">Inscription</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                        <tr>
                            <td>{{ $registration->last_name }}</td>
                            <td>{{ $registration->first_name }}</td>
                            <td class="insc-hidden-sm">{{ $registration->city ?? '—' }}</td>
                            <td class="insc-hidden-sm">{{ $registration->editionSection?->section ?? '—' }}</td>
                            <td>
                                @php $p = $registration->payment_status?->value; @endphp
                                <span class="insc-badge {{ $p === 'paid' ? 'insc-badge-paid' : ($p === 'partial' ? 'insc-badge-partial' : ($p === 'unpaid' ? 'insc-badge-unpaid' : '')) }}">
                                    @switch($p)
                                        @case('paid') Payé @break
                                        @case('partial') Partiellement payé @break
                                        @case('unpaid') Non payé @break
                                        @default — @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="insc-hidden-sm">
                                @php $s = $registration->registration_status?->value; @endphp
                                <span class="insc-badge {{ $s === 'confirmed' ? 'insc-badge-confirmed' : ($s === 'pending' ? 'insc-badge-pending' : ($s === 'cancelled' ? 'insc-badge-cancelled' : '')) }}">
                                    @switch($s)
                                        @case('confirmed') Confirmée @break
                                        @case('pending') En attente @break
                                        @case('cancelled') Annulée @break
                                        @default — @break
                                    @endswitch
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="insc-empty">Aucun inscrit pour le moment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($registrations->hasPages())
            <div class="insc-pagination">
                {{ $registrations->links() }}
            </div>
        @endif

    </div>
</div>

@endsection
