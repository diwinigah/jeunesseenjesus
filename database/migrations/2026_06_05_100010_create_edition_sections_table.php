<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create section-specific pricing for each camp edition.
     */
    public function up(): void
    {
        Schema::create('edition_sections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('camp_edition_id')->constrained()->cascadeOnDelete();
            $table->enum('section', ['college', 'lycee', 'universitaire', 'adulte', 'invite', 'famille']);
            $table->decimal('price', 10, 2);
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('camp_edition_id');
            $table->index('section');
            $table->unique(['camp_edition_id', 'section']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('edition_sections');
    }
};
