<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create annual camp editions used to scope public registrations.
     */
    public function up(): void
    {
        Schema::create('camp_editions', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->year('year')->index();
            $table->text('description')->nullable();
            $table->dateTime('registration_open_at')->index();
            $table->dateTime('registration_close_at')->index();
            $table->date('camp_start_date')->nullable();
            $table->date('camp_end_date')->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->string('currency', 10)->default('XOF')->index();
            $table->enum('status', ['draft', 'open', 'closed', 'archived'])->default('draft')->index();
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();

            $table->index(['registration_open_at', 'registration_close_at']);
            $table->index(['status', 'is_active']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('camp_editions');
    }
};
