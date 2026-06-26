@extends('layouts.app')

@section('title', isset($edition) && $edition ? $edition->name . ' - Liste des inscrits' : 'Inscrits')

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
<div class="inscrits-page">

    <div class="inscrits-header">
        <h1>Liste des inscrits</h1>
        @if($edition)
            <div class="inscrits-edition">{{ $edition->name }}</div>

        @endif
    </div>

    @if(!$edition || $registrations->isEmpty())
        <div class="inscrits-empty">
            Aucun inscrit confirmé pour le moment.
        </div>

    @else

        @if($stats && ($stats['eleve'] > 0 || $stats['etudiant'] > 0 || $stats['adulte'] > 0))
        <div class="inscrits-stats">
            <div class="stat-card">
                <span class="stat-number">{{ $stats['eleve'] }}</span>
                <div class="stat-label">Élèves</div>
            </div>

            <div class="stat-card">
                <span class="stat-number">{{ $stats['etudiant'] }}</span>
                <div class="stat-label">Étudiants</div>
            </div>

            <div class="stat-card">
                <span class="stat-number">{{ $stats['adulte'] }}</span>
                <div class="stat-label">Adultes</div>
            </div>

            <div class="stat-card stat-total">
                <span class="stat-number">{{ $stats['total'] }}</span>
                <div class="stat-label">Total</div>
            </div>
        </div>

        @else
        <div class="inscrits-stats">
            <div class="stat-card stat-total">
                <span class="stat-number">{{ $stats['total'] }}</span>
                <div class="stat-label">Total inscrits</div>
            </div>
        </div>

        @endif

        <div class="inscrits-table-wrapper">
            <table class="inscrits-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Ville</th>
                        <th>Section</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $index => $registration)
                    <tr class="animate-up">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ Str::upper($registration->last_name) }}</td>
                        <td>{{ Str::ucfirst(Str::lower($registration->first_name)) }}</td>
                        <td>{{ $registration->city }}</td>
                        <td>
                            @if($registration->editionSection)
                                <span class="section-badge">{{ $registration->editionSection->section->label() }}</span>
                            @else
                                <span class="section-invite">Invité</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @endif

</div>

<style>
.inscrits-page {
    max-width: 900px;
    margin: 2rem auto;
    padding: 0 1rem;
}
.inscrits-header {
    text-align: center;
    margin-bottom: 2rem;
}
.inscrits-header h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #3D2B1F;
}
.inscrits-edition {
    color: #E8490F;
    font-weight: 600;
    margin-top: 0.25rem;
}
.inscrits-stats {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 2rem;
}
.stat-card {
    background: #fff;
    border: 2px solid #f0e8e4;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    text-align: center;
    min-width: 110px;
}
.stat-total {
    border-color: #E8490F;
}
.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 800;
    color: #3D2B1F;
}
.stat-total .stat-number {
    color: #E8490F;
}
.stat-label {
    font-size: 0.85rem;
    color: #888;
    margin-top: 0.25rem;
}
.inscrits-table-wrapper {
    overflow-x: auto;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
}
.inscrits-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}
.inscrits-table thead tr {
    background: #3D2B1F;
    color: #fff;
}
.inscrits-table th {
    padding: 0.85rem 1rem;
    text-align: left;
    font-size: 0.9rem;
    font-weight: 600;
}
.inscrits-table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f0e8e4;
    font-size: 0.95rem;
}
.inscrits-table tbody tr:hover {
    background: #fdf6f3;
}
.inscrits-table tbody tr:last-child td {
    border-bottom: none;
}
.section-badge {
    background: #E8490F;
    color: #fff;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}
.section-invite {
    color: #aaa;
    font-style: italic;
    font-size: 0.85rem;
}
.inscrits-empty {
    text-align: center;
    color: #888;
    padding: 3rem;
}
@media (max-width: 640px) {
    .inscrits-table th,
    .inscrits-table td {
        padding: 0.6rem 0.5rem;
        font-size: 0.85rem;
    }
    .stat-card { min-width: 80px; padding: 0.75rem; }
    .stat-number { font-size: 1.5rem; }
}

</style>

@endsection
