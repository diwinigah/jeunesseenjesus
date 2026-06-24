<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_payments', function (Blueprint $table): void {
            $table->dropForeign(['registration_id']);
            $table->foreign('registration_id')
                ->references('id')
                ->on('registrations')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('registration_payments', function (Blueprint $table): void {
            $table->dropForeign(['registration_id']);
            $table->foreign('registration_id')
                ->references('id')
                ->on('registrations')
                ->restrictOnDelete();
        });
    }
};
