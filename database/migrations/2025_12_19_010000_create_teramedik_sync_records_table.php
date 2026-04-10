<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk menyimpan mapping ID antara sistem lokal dan Teramedik.
     * Digunakan untuk tracking sync bidirectional.
     */
    public function up(): void
    {
        Schema::create('teramedik_sync_records', function (Blueprint $table) {
            $table->id();
            
            // Local reference
            $table->string('local_table', 50); // 'birth_records', 'doctors', etc.
            $table->unsignedBigInteger('local_id');
            
            // Teramedik reference
            $table->string('teramedik_id', 100)->nullable();
            $table->string('teramedik_rm', 50)->nullable(); // No. RM dari Teramedik (untuk bayi)
            $table->string('teramedik_type', 50)->nullable(); // Tipe entity di Teramedik
            
            // Sync status
            $table->enum('sync_status', ['pending', 'synced', 'failed', 'conflict'])->default('pending');
            $table->text('sync_error')->nullable(); // Error message jika gagal
            $table->timestamp('last_synced_at')->nullable();
            
            // Audit
            $table->foreignId('synced_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            
            // Indexes
            $table->unique(['local_table', 'local_id'], 'teramedik_sync_local_unique');
            $table->index(['teramedik_id'], 'teramedik_sync_teramedik_id_index');
            $table->index(['sync_status'], 'teramedik_sync_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teramedik_sync_records');
    }
};
