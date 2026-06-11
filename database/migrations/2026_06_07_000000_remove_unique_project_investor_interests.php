<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove unique constraint to allow investors to invest multiple times in same project.
     */
    public function up(): void
    {
        Schema::table('project_investor_interests', function (Blueprint $table): void {
            $table->dropUnique(['project_id', 'investor_user_id']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('project_investor_interests', function (Blueprint $table): void {
            $table->unique(['project_id', 'investor_user_id']);
        });
    }
};
