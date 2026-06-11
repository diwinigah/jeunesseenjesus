<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Scheduler pour la clôture automatique des éditions de camp expirées
 * Exécuté toutes les heures
 */
Schedule::command('editions:close-expired')
    ->hourly()
    ->withoutOverlapping();
