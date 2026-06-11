<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create fundable projects displayed publicly and managed in the back-office.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary', 500)->nullable();
            $table->longText('description')->nullable();
            $table->decimal('funding_goal', 12, 2)->default(0);
            $table->decimal('funded_amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('XOF')->index();
            $table->enum('status', ['draft', 'published', 'funded', 'archived'])->default('draft')->index();
            $table->string('featured_image_path', 500)->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->dateTime('published_at')->nullable()->index();
            $table->timestamps();

            $table->index(['status', 'is_featured']);
            $table->index(['status', 'published_at']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
