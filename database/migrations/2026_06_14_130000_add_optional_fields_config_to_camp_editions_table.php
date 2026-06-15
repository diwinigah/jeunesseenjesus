<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camp_editions', function (Blueprint $table): void {
            $table->boolean('show_days_presence')->default(false)->after('cover_image_path');
            $table->boolean('show_children_count')->default(false)->after('show_days_presence');
            $table->boolean('show_bus_departure')->default(false)->after('show_children_count');
            $table->boolean('show_participant_type')->default(false)->after('show_bus_departure');
        });
    }

    public function down(): void
    {
        Schema::table('camp_editions', function (Blueprint $table): void {
            $table->dropColumn(['show_days_presence', 'show_children_count', 'show_bus_departure', 'show_participant_type']);
        });
    }
};
