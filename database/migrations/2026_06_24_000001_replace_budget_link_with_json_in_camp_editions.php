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
            // Supprimer les deux colonnes simples
            $table->dropColumn(['budget_link_label', 'budget_link_url']);
            // Ajouter une colonne JSON pour plusieurs liens
            $table->json('external_links')->nullable()->after('title_paiement');
        });
    }

    public function down(): void
    {
        Schema::table('camp_editions', function (Blueprint $table) {
            $table->dropColumn('external_links');
            $table->string('budget_link_label')->nullable();
            $table->string('budget_link_url')->nullable();
        });
    }
};
