<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

final class MakeBourseFieldsNullableInCampEditionsTable extends Migration
{
    public function up(): void
    {
        Schema::table('camp_editions', function (Blueprint $table) {
            DB::statement('ALTER TABLE camp_editions MODIFY bourse_pleine_label VARCHAR(255) NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY bourse_pleine_desc VARCHAR(255) NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY bourse_partielle_label VARCHAR(255) NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY bourse_partielle_desc VARCHAR(255) NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY categorie_adulte_label VARCHAR(255) NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY categorie_etudiant_label VARCHAR(255) NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY categorie_lycee_label VARCHAR(255) NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY categorie_enfant_label VARCHAR(255) NULL');
        });
    }

    public function down(): void
    {
        Schema::table('camp_editions', function (Blueprint $table) {
            DB::statement('ALTER TABLE camp_editions MODIFY bourse_pleine_label VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY bourse_pleine_desc VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY bourse_partielle_label VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY bourse_partielle_desc VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY categorie_adulte_label VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY categorie_etudiant_label VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY categorie_lycee_label VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE camp_editions MODIFY categorie_enfant_label VARCHAR(255) NOT NULL');
        });
    }
}
