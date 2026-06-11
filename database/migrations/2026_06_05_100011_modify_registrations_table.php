<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replace the free-text section with a constrained edition section reference.
     */
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table): void {
            if (! Schema::hasColumn('registrations', 'edition_section_id')) {
                $table->unsignedBigInteger('edition_section_id')
                    ->nullable()
                    ->after('camp_edition_id')
                    ->index();

                $table->foreign('edition_section_id')
                    ->references('id')
                    ->on('edition_sections')
                    ->restrictOnDelete();
            }
        });

        if (Schema::hasColumn('registrations', 'section')) {
            DB::table('registrations')
                ->whereNotNull('section')
                ->orderBy('id')
                ->select(['id', 'camp_edition_id', 'section'])
                ->each(function (object $registration): void {
                    $editionSectionId = DB::table('edition_sections')
                        ->where('camp_edition_id', $registration->camp_edition_id)
                        ->where('section', $registration->section)
                        ->value('id');

                    if ($editionSectionId !== null) {
                        DB::table('registrations')
                            ->where('id', $registration->id)
                            ->update(['edition_section_id' => $editionSectionId]);
                    }
                });
        }

        $unmappedRegistrations = DB::table('registrations')
            ->whereNull('edition_section_id')
            ->count();

        if ($unmappedRegistrations > 0) {
            throw new RuntimeException(
                'Cannot make registrations.edition_section_id required: some registrations have no matching edition section.',
            );
        }

        if (Schema::hasColumn('registrations', 'section')) {
            Schema::table('registrations', function (Blueprint $table): void {
                $table->dropColumn('section');
            });
        }

        DB::statement('ALTER TABLE registrations MODIFY edition_section_id BIGINT UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('registrations', 'section')) {
            Schema::table('registrations', function (Blueprint $table): void {
                $table->string('section', 150)->nullable()->index()->after('city');
            });
        }

        if (Schema::hasColumn('registrations', 'edition_section_id')) {
            DB::table('registrations')
                ->join('edition_sections', 'registrations.edition_section_id', '=', 'edition_sections.id')
                ->update([
                    'registrations.section' => DB::raw('edition_sections.section'),
                ]);

            Schema::table('registrations', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('edition_section_id');
            });
        }
    }
};
