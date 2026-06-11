<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create public camp registrations without participant user accounts.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('camp_edition_id')->constrained()->restrictOnDelete();
            $table->string('registration_number', 50)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->enum('gender', ['male', 'female', 'other'])->index();
            $table->string('phone', 50)->index();
            $table->string('whatsapp_phone', 50)->nullable()->index();
            $table->string('city', 150)->nullable()->index();
            $table->string('section', 150)->nullable()->index();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid')->index();
            $table->enum('registration_status', ['pending', 'confirmed', 'cancelled'])->default('pending')->index();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->dateTime('submitted_at')->index();
            $table->dateTime('confirmed_at')->nullable()->index();
            $table->timestamps();

            $table->index(['camp_edition_id', 'registration_status']);
            $table->index(['camp_edition_id', 'payment_status']);
            $table->index(['last_name', 'first_name']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
