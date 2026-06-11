<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create public partnership requests before administrative validation.
     */
    public function up(): void
    {
        Schema::create('partner_requests', function (Blueprint $table): void {
            $table->id();
            $table->string('organization_name');
            $table->string('contact_name');
            $table->string('email')->nullable()->index();
            $table->string('phone', 50)->index();
            $table->enum('type', ['church', 'company', 'association', 'individual', 'other'])->index();
            $table->string('website_url', 500)->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['new', 'reviewed', 'accepted', 'rejected', 'archived'])->default('new')->index();
            $table->foreignId('converted_partner_id')->nullable()->constrained('partners')->restrictOnDelete();
            $table->text('admin_notes')->nullable();
            $table->dateTime('submitted_at')->index();
            $table->timestamps();

            $table->index(['status', 'submitted_at']);
            $table->index(['organization_name', 'contact_name']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_requests');
    }
};
