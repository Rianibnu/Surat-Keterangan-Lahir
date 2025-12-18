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
        Schema::create('skls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('birth_record_id')->constrained('birth_records')->onDelete('cascade');
            $table->string('document_number');
            $table->date('issue_date');
            $table->string('signer_name');
            $table->string('signer_role')->default('Dokter Penanggung Jawab Persalinan');
            $table->boolean('is_signed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skls');
    }
};
