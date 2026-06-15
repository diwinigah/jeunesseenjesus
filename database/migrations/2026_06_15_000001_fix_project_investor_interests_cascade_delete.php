<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_investor_interests', function (Blueprint $table): void {
            $table->dropForeign(['project_id']);
            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('project_investor_interests', function (Blueprint $table): void {
            $table->dropForeign(['project_id']);
            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->restrictOnDelete();
        });
    }
};
