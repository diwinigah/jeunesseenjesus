<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camp_editions', function (Blueprint $table) {
            // Tableau budget prévisionnel (JSON)
            $table->json('budget_entries')->nullable()->after('nature_contributions');
            $table->json('budget_expenses')->nullable()->after('budget_entries');

            // Répartition participants par catégorie
            $table->unsignedInteger('participants_adultes')->default(0)->after('budget_expenses');
            $table->unsignedInteger('participants_etudiants')->default(0)->after('participants_adultes');
            $table->unsignedInteger('participants_lycee')->default(0)->after('participants_etudiants');
            $table->unsignedInteger('participants_enfants')->default(0)->after('participants_lycee');

            // Répartition géographique (JSON)
            $table->json('participants_geo')->nullable()->after('participants_enfants');
        });
    }

    public function down(): void
    {
        Schema::table('camp_editions', function (Blueprint $table) {
            $table->dropColumn([
                'budget_entries', 'budget_expenses',
                'participants_adultes', 'participants_etudiants',
                'participants_lycee', 'participants_enfants',
                'participants_geo',
            ]);
        });
    }
};
