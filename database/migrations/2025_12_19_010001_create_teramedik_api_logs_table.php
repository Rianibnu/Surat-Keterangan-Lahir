<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk logging semua API calls ke Teramedik.
     * Berguna untuk debugging dan audit.
     */
    public function up(): void
    {
        Schema::create('teramedik_api_logs', function (Blueprint $table) {
            $table->id();
            
            // Request info
            $table->string('method', 10); // GET, POST, PUT, DELETE
            $table->string('endpoint', 500);
            $table->json('request_payload')->nullable();
            
            // Response info
            $table->json('response_payload')->nullable();
            $table->unsignedSmallInteger('response_code');
            
            // Performance
            $table->unsignedInteger('duration_ms')->default(0);
            
            // Context
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes
            $table->index(['created_at'], 'teramedik_api_logs_created_index');
            $table->index(['response_code'], 'teramedik_api_logs_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teramedik_api_logs');
    }
};
