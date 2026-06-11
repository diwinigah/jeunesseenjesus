<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\CampEdition;
use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class RegistrationPdfExport
{
    /**
     * Générer et télécharger le PDF des inscriptions
     */
    public function download(CampEdition $edition): Response
    {
        // Récupérer les inscriptions de l'édition
        $registrations = Registration::query()
            ->where('camp_edition_id', $edition->id)
            ->with(['editionSection'])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Calculer les totaux
        $totalAmount = $registrations->sum('total_amount');
        $totalPaid = $registrations->sum('paid_amount');
        $totalRemaining = $totalAmount - $totalPaid;

        // Charger la vue et générer le PDF
        $pdf = Pdf::loadView(
            'exports.registrations-pdf',
            [
                'registrations' => $registrations,
                'edition' => $edition,
                'exportDate' => now()->format('d/m/Y H:i'),
                'totalAmount' => $totalAmount,
                'totalPaid' => $totalPaid,
                'totalRemaining' => $totalRemaining,
                'totalCount' => $registrations->count(),
            ]
        );

        // Définir les options du PDF
        $pdf->setPaper('a4', 'landscape');

        // Générer le nom du fichier
        $filename = sprintf(
            'inscriptions-%s-%s.pdf',
            $edition->slug,
            now()->format('Y-m-d-His')
        );

        return $pdf->download($filename);
    }
}
