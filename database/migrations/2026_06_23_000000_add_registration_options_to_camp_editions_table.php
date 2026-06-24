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
            // Lien activités du camp
            $table->string('activities_link_label')->nullable()->after('description');
            // ex: "Programme et activités du camp"
            $table->string('activities_link_url')->nullable()->after('activities_link_label');

            // 2ème méthode d'inscription
            $table->enum('registration_mode', ['internal', 'external'])
                  ->default('internal')
                  ->after('activities_link_url');
            // 'internal' = formulaire actuel
            // 'external' = Google Form ou autre lien externe

            $table->string('external_registration_label')->nullable()->after('registration_mode');
            // ex: "S'inscrire via Google Form"
            $table->string('external_registration_url')->nullable()->after('external_registration_label');
        });
    }

    public function down(): void
    {
        Schema::table('camp_editions', function (Blueprint $table) {
            $table->dropColumn([
                'activities_link_label',
                'activities_link_url',
                'registration_mode',
                'external_registration_label',
                'external_registration_url',
            ]);
        });
    }
};
