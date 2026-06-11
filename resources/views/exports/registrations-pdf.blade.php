<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscriptions - {{ $edition->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
            padding: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .header-left h1 {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .header-left p {
            font-size: 11px;
            color: #666;
            margin: 2px 0;
        }

        .header-right {
            text-align: right;
        }

        .header-right .logo {
            font-size: 14px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 5px;
        }

        .header-right .date {
            font-size: 10px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        thead {
            background-color: #34495e;
            color: white;
        }

        th {
            padding: 8px;
            text-align: left;
            font-weight: 600;
            font-size: 9px;
            border: 1px solid #34495e;
        }

        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 9px;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:hover {
            background-color: #e8eef5;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .amount {
            font-weight: 600;
            text-align: right;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: 600;
        }

        .status-partial {
            background-color: #fff3cd;
            color: #856404;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: 600;
        }

        .status-unpaid {
            background-color: #f8d7da;
            color: #721c24;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: 600;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #2c3e50;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
        }

        .footer-item {
            text-align: center;
        }

        .footer-item h3 {
            font-size: 10px;
            color: #666;
            margin-bottom: 3px;
            font-weight: 600;
        }

        .footer-item p {
            font-size: 12px;
            color: #2c3e50;
            font-weight: 700;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <div class="header-left">
            <h1>📋 Inscriptions</h1>
            <p><strong>Édition:</strong> {{ $edition->name }}</p>
            <p><strong>Période:</strong> 
                {{ $edition->start_date->format('d/m/Y') }} 
                à 
                {{ $edition->end_date->format('d/m/Y') }}
            </p>
        </div>
        <div class="header-right">
            <div class="logo">✝️ Jeunesse en Jésus</div>
            <div class="date">Export: {{ $exportDate }}</div>
        </div>
    </div>

    <!-- Tableau des inscriptions -->
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">#</th>
                <th style="width: 15%;">Nom complet</th>
                <th style="width: 12%;">Ville</th>
                <th style="width: 12%;">Section</th>
                <th class="text-center" style="width: 12%;">Statut paiement</th>
                <th class="amount" style="width: 11%;">Montant total</th>
                <th class="amount" style="width: 11%;">Montant payé</th>
                <th class="amount" style="width: 11%;">Montant restant</th>
                <th style="width: 11%;">Date inscription</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $index => $registration)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $registration->first_name }} {{ $registration->last_name }}</strong>
                    </td>
                    <td>{{ $registration->city ?? '-' }}</td>
                    <td>{{ $registration->editionSection?->name ?? '-' }}</td>
                    <td class="text-center">
                        @php
                            $remaining = $registration->total_amount - $registration->paid_amount;
                            if ($remaining <= 0) {
                                $statusClass = 'status-paid';
                                $statusText = 'Payé ✓';
                            } elseif ($registration->paid_amount > 0) {
                                $statusClass = 'status-partial';
                                $statusText = 'Partiel';
                            } else {
                                $statusClass = 'status-unpaid';
                                $statusText = 'Non payé';
                            }
                        @endphp
                        <span class="{{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                    <td class="amount">{{ number_format($registration->total_amount, 2, ',', ' ') }} €</td>
                    <td class="amount">{{ number_format($registration->paid_amount, 2, ',', ' ') }} €</td>
                    <td class="amount">
                        @php
                            $remaining = max(0, $registration->total_amount - $registration->paid_amount);
                        @endphp
                        {{ number_format($remaining, 2, ',', ' ') }} €
                    </td>
                    <td>{{ $registration->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="background-color: #f8f9fa; font-style: italic;">
                        Aucune inscription pour cette édition
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pied de page avec résumés -->
    <div class="footer">
        <div class="footer-item">
            <h3>Nombre d'inscrits</h3>
            <p>{{ $totalCount }} inscriptions</p>
        </div>
        <div class="footer-item">
            <h3>Total collecté</h3>
            <p>{{ number_format($totalPaid, 2, ',', ' ') }} €</p>
        </div>
        <div class="footer-item">
            <h3>Total attendu</h3>
            <p>{{ number_format($totalAmount, 2, ',', ' ') }} €</p>
        </div>
    </div>

    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #ddd; text-align: center; font-size: 8px; color: #999;">
        <p>Document généré le {{ now()->format('d/m/Y \à H:i:s') }} par la plateforme Jeunesse en Jésus</p>
    </div>
</body>
</html>
