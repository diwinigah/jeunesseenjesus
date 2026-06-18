<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class AddBourseLabelsToCampEditionsTable extends Migration
{
    public function up(): void
    {
        Schema::table('camp_editions', function (Blueprint $table) {
            $table->string('bourse_pleine_label')->default('Bourse Pleine')->after('bourse_pleine_amount');
            $table->string('bourse_pleine_desc')->default('Couvrez l\'intégralité des frais d\'un jeune')->after('bourse_pleine_label');
            $table->string('bourse_partielle_label')->default('Bourse Partielle')->after('bourse_pleine_desc');
            $table->string('bourse_partielle_desc')->default('Contribuez selon votre cœur')->after('bourse_partielle_label');
            $table->string('categorie_adulte_label')->default('Adulte')->after('bourse_adulte_amount');
            $table->string('categorie_etudiant_label')->default('Étudiant')->after('bourse_etudiant_amount');
            $table->string('categorie_lycee_label')->default('Lycée / Collège')->after('bourse_lycee_amount');
            $table->string('categorie_enfant_label')->default('Enfant')->after('bourse_enfant_amount');
        });
    }

    public function down(): void
    {
        Schema::table('camp_editions', function (Blueprint $table) {
            $table->dropColumn([
                'bourse_pleine_label', 'bourse_pleine_desc',
                'bourse_partielle_label', 'bourse_partielle_desc',
                'categorie_adulte_label', 'categorie_etudiant_label',
                'categorie_lycee_label', 'categorie_enfant_label',
            ]);
        });
    }
}
