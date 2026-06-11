<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add logo_path column to partner_requests table.
     */
    public function up(): void
    {
        Schema::table('partner_requests', function (Blueprint $table): void {
            $table->string('logo_path', 500)
                ->nullable()
                ->after('website_url');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('partner_requests', function (Blueprint $table): void {
            $table->dropColumn('logo_path');
        });
    }
};
