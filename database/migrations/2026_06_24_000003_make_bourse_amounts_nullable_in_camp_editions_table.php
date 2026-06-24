<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

final class MakeBourseAmountsNullableInCampEditionsTable extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE camp_editions MODIFY bourse_pleine_amount BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE camp_editions MODIFY bourse_adulte_amount BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE camp_editions MODIFY bourse_etudiant_amount BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE camp_editions MODIFY bourse_lycee_amount BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE camp_editions MODIFY bourse_enfant_amount BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE camp_editions MODIFY bourse_pleine_amount BIGINT UNSIGNED NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE camp_editions MODIFY bourse_adulte_amount BIGINT UNSIGNED NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE camp_editions MODIFY bourse_etudiant_amount BIGINT UNSIGNED NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE camp_editions MODIFY bourse_lycee_amount BIGINT UNSIGNED NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE camp_editions MODIFY bourse_enfant_amount BIGINT UNSIGNED NOT NULL DEFAULT 0');
    }
}
