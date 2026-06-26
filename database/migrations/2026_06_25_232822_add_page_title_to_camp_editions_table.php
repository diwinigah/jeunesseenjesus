<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('camp_editions', function (Blueprint $table) {
            $table->string('registration_page_title')
                  ->nullable()
                  ->default(null)
                  ->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('camp_editions', function (Blueprint $table) {
            $table->dropColumn('registration_page_title');
        });
    }
};
