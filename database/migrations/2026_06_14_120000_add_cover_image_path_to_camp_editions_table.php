<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the nullable cover image path to camp_editions.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('camp_editions', 'cover_image_path')) {
            Schema::table('camp_editions', function (Blueprint $table): void {
                $table->string('cover_image_path')->nullable()->after('description');
            });
        }
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        if (Schema::hasColumn('camp_editions', 'cover_image_path')) {
            Schema::table('camp_editions', function (Blueprint $table): void {
                $table->dropColumn('cover_image_path');
            });
        }
    }
};
