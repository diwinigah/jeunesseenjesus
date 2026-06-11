<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CampEdition;
use App\Enums\CampEditionStatus;
use Illuminate\Console\Command;

class CloseExpiredCampEditions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'editions:close-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ferme automatiquement les éditions de camp dont la date d\'inscription est expirée';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = now();
        $closedCount = 0;

        // Chercher les éditions expirées
        $editions = CampEdition::query()
            ->whereNotIn('status', [
                CampEditionStatus::Closed->value,
                CampEditionStatus::Archived->value,
            ])
            ->where('registration_close_at', '<', $now)
            ->get();

        // Fermer chaque édition trouvée
        foreach ($editions as $edition) {
            $edition->status = CampEditionStatus::Closed;
            $edition->saveQuietly();

            $this->info(
                sprintf(
                    'Édition "%s" fermée (ID: %d) - Date expiration: %s',
                    $edition->name,
                    $edition->id,
                    $edition->registration_close_at->format('d/m/Y H:i')
                )
            );

            $closedCount++;
        }

        // Log et affichage du résultat
        if ($closedCount > 0) {
            $this->info(sprintf(
                '✓ %d édition(s) fermée(s) automatiquement',
                $closedCount
            ));

            \Log::info(sprintf(
                'Scheduler: %d édition(s) de camp fermée(s) automatiquement',
                $closedCount
            ));
        } else {
            $this->info('✓ Aucune édition à fermer pour le moment');
        }

        return self::SUCCESS;
    }
}
