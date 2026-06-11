<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Modifie la contrainte de clé étrangère converted_partner_id
     * pour cascader la suppression.
     */
    public function up(): void
    {
        Schema::table('partner_requests', function (Blueprint $table): void {
            // Supprimer l'ancienne contrainte
            $table->dropForeign(['converted_partner_id']);
            
            // Ajouter la nouvelle avec cascadeOnDelete
            $table->foreign('converted_partner_id')
                ->references('id')
                ->on('partners')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse la migration.
     */
    public function down(): void
    {
        Schema::table('partner_requests', function (Blueprint $table): void {
            // Supprimer la nouvelle contrainte
            $table->dropForeign(['converted_partner_id']);
            
            // Restaurer l'ancienne avec restrictOnDelete
            $table->foreign('converted_partner_id')
                ->references('id')
                ->on('partners')
                ->restrictOnDelete();
        });
    }
};
