<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create manually validated offline payment records for registrations.
     */
    public function up(): void
    {
        Schema::create('registration_payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('registration_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('XOF')->index();
            $table->enum('payment_method', ['cash', 'mobile_money', 'bank_transfer', 'cheque', 'other'])->index();
            $table->string('reference')->nullable()->index();
            $table->dateTime('paid_at')->index();
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['registration_id', 'paid_at']);
            $table->index(['validated_by', 'paid_at']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_payments');
    }
};
