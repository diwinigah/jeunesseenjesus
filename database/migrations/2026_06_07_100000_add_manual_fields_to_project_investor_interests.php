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
        Schema::table('project_investor_interests', function (Blueprint $table): void {
            $table->string('manual_name', 255)->nullable()->after('investor_user_id');
            $table->string('manual_organisation', 255)->nullable()->after('manual_name');
            $table->string('manual_email', 255)->nullable()->after('manual_organisation');
            $table->string('manual_phone', 50)->nullable()->after('manual_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_investor_interests', function (Blueprint $table): void {
            $table->dropColumn(['manual_name', 'manual_organisation', 'manual_email', 'manual_phone']);
        });
    }
};
