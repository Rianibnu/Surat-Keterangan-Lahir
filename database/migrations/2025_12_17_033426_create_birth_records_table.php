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
        Schema::create('birth_records', function (Blueprint $table) {
            $table->id();
            // Baby Data
            $table->string('baby_name');
            $table->date('birth_date');
            $table->time('birth_time');
            $table->string('hospital_name')->default('RS Unggul Karsa Medika');
            $table->string('medical_record_no');
            $table->string('gender'); // L/P or Laki-laki/Perempuan
            $table->integer('child_order');
            $table->string('delivery_method');
            $table->string('baby_blood_type')->nullable();
            $table->double('weight'); // gram
            $table->double('length'); // cm
            $table->double('head_circ'); // cm
            $table->double('chest_circ'); // cm

            // Mother Data
            $table->string('mother_name');
            $table->string('mother_medical_record_no')->nullable();
            $table->string('mother_ktp')->nullable();
            $table->text('mother_address')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_blood_type')->nullable();

            // Father Data
            $table->string('father_name');
            $table->string('father_ktp')->nullable();
            $table->text('father_address')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_blood_type')->nullable();

            // Foreign Keys
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birth_records');
    }
};
