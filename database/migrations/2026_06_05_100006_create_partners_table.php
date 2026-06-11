<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create validated partners that can be displayed publicly.
     */
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['church', 'company', 'association', 'individual', 'other'])->index();
            $table->text('description')->nullable();
            $table->string('logo_path', 500)->nullable();
            $table->string('website_url', 500)->nullable();
            $table->string('email')->nullable()->index();
            $table->string('phone', 50)->nullable()->index();
            $table->boolean('is_public')->default(true)->index();
            $table->unsignedInteger('display_order')->default(0)->index();
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active')->index();
            $table->timestamps();

            $table->index(['status', 'is_public', 'display_order']);
            $table->index(['name', 'type']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
