<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('skls', 'uuid')) {
            Schema::table('skls', function (Blueprint $table) {
                $table->uuid('uuid')->after('id')->nullable();
            });
        }

        // Backfill existing records
        $skls = \Illuminate\Support\Facades\DB::table('skls')->whereNull('uuid')->get();
        foreach ($skls as $skl) {
            \Illuminate\Support\Facades\DB::table('skls')
                ->where('id', $skl->id)
                ->update(['uuid' => (string) \Illuminate\Support\Str::uuid()]);
        }

        // Add constraints if not already unique/not nullable (Best effort)
        // Note: changing column usually requires DBAL, skipping strict change to avoid dependency issues if unnecessary
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skls', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
