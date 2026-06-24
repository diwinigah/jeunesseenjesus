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
            // Titres personnalisables des sections
            $table->string('title_bourses')->nullable()->default(null)->after('sponsoring_salutation');
            $table->string('title_frais')->nullable()->default(null)->after('title_bourses');
            $table->string('title_nature')->nullable()->default(null)->after('title_frais');
            $table->string('title_paiement')->nullable()->default(null)->after('title_nature');

            // Lien budget prévisionnel
            $table->string('budget_link_label')->nullable()->default(null)->after('title_paiement');
            $table->string('budget_link_url')->nullable()->default(null)->after('budget_link_label');
        });
    }

    public function down(): void
    {
        Schema::table('camp_editions', function (Blueprint $table) {
            $table->dropColumn([
                'title_bourses',
                'title_frais',
                'title_nature',
                'title_paiement',
                'budget_link_label',
                'budget_link_url',
            ]);
        });
    }
};
