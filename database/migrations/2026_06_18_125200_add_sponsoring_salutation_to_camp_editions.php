<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('camp_editions', function (Blueprint $table): void {
            $table->text('sponsoring_salutation')->nullable()->after('sponsoring_verse');
        });
    }

    public function down(): void
    {
        Schema::table('camp_editions', function (Blueprint $table): void {
            $table->dropColumn('sponsoring_salutation');
        });
    }
};
