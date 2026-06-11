<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create authenticated investor accounts for the dedicated "investor" guard.
     */
    public function up(): void
    {
        Schema::create('investor_users', function (Blueprint $table): void {
            $table->id();
            $table->enum('type', ['individual', 'organization'])->default('individual')->index();
            $table->string('name');
            $table->string('organization_name')->nullable()->index();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 50)->index();
            $table->string('city', 150)->nullable()->index();
            $table->string('country', 150)->nullable()->index();
            $table->enum('preferred_contact_method', ['phone', 'whatsapp', 'email'])->default('email')->index();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->index();
            $table->text('notes')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index(['name', 'organization_name']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('investor_users');
    }
};
