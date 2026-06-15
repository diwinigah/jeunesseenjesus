<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table): void {
            $table->json('days_presence')->nullable()->after('city');
            $table->unsignedTinyInteger('children_count')->nullable()->after('days_presence');
            $table->boolean('bus_departure')->nullable()->after('children_count');
            $table->string('participant_type')->nullable()->after('bus_departure');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table): void {
            $table->dropColumn(['days_presence', 'children_count', 'bus_departure', 'participant_type']);
        });
    }
};
