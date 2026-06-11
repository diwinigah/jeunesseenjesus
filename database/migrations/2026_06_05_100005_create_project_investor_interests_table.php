<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create project funding interests linking investors to one or many projects.
     */
    public function up(): void
    {
        Schema::create('project_investor_interests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->constrained()->restrictOnDelete();
            $table->foreignId('investor_user_id')->constrained('investor_users')->restrictOnDelete();
            $table->decimal('intended_amount', 12, 2)->nullable();
            $table->decimal('committed_amount', 12, 2)->nullable();
            $table->string('currency', 10)->default('XOF')->index();
            $table->enum('status', ['new', 'contacted', 'pledged', 'paid', 'cancelled'])->default('new')->index();
            $table->text('message')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'investor_user_id']);
            $table->index(['investor_user_id', 'status']);
            $table->index(['project_id', 'status']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_investor_interests');
    }
};
