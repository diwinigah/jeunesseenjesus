<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camp_editions', function (Blueprint $table) {
            // Infos générales sponsoring
            $table->string('sponsoring_theme')->nullable()->after('description');
            $table->text('sponsoring_intro')->nullable()->after('sponsoring_theme');
            $table->string('sponsoring_verse')->nullable()->after('sponsoring_intro');
            $table->boolean('show_sponsoring_page')->default(false)->after('sponsoring_verse');

            // Budget
            $table->unsignedBigInteger('budget_total')->default(0)->after('show_sponsoring_page');
            $table->unsignedBigInteger('budget_collected')->default(0)->after('budget_total');
            $table->unsignedBigInteger('participants_target')->default(0)->after('budget_collected');
            $table->unsignedBigInteger('participants_sponsored')->default(0)->after('participants_target');

            // Bourses
            $table->unsignedBigInteger('bourse_pleine_amount')->default(40000)->after('participants_sponsored');
            $table->unsignedBigInteger('bourse_adulte_amount')->default(30000)->after('bourse_pleine_amount');
            $table->unsignedBigInteger('bourse_etudiant_amount')->default(20000)->after('bourse_adulte_amount');
            $table->unsignedBigInteger('bourse_lycee_amount')->default(15000)->after('bourse_etudiant_amount');
            $table->unsignedBigInteger('bourse_enfant_amount')->default(10000)->after('bourse_lycee_amount');

            // Moyens de paiement
            $table->string('payment_flooz')->nullable()->after('bourse_enfant_amount');
            $table->string('payment_mixx')->nullable()->after('payment_flooz');
            $table->string('payment_iban')->nullable()->after('payment_mixx');
            $table->string('payment_paypal')->nullable()->after('payment_iban');
            $table->string('payment_account_name')->nullable()->after('payment_paypal');
            $table->string('payment_account_number')->nullable()->after('payment_account_name');

            // Contact
            $table->string('sponsoring_contact_phone')->nullable()->after('payment_account_number');
            $table->string('sponsoring_contact_email')->nullable()->after('sponsoring_contact_phone');

            // Apports en nature (JSON)
            $table->json('nature_contributions')->nullable()->after('sponsoring_contact_email');
        });
    }

    public function down(): void
    {
        Schema::table('camp_editions', function (Blueprint $table) {
            $table->dropColumn([
                'sponsoring_theme', 'sponsoring_intro', 'sponsoring_verse',
                'show_sponsoring_page', 'budget_total', 'budget_collected',
                'participants_target', 'participants_sponsored',
                'bourse_pleine_amount', 'bourse_adulte_amount',
                'bourse_etudiant_amount', 'bourse_lycee_amount', 'bourse_enfant_amount',
                'payment_flooz', 'payment_mixx', 'payment_iban', 'payment_paypal',
                'payment_account_name', 'payment_account_number',
                'sponsoring_contact_phone', 'sponsoring_contact_email',
                'nature_contributions',
            ]);
        });
    }
};
