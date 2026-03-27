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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->string('employee_code')->nullable();
            $table->string('employee_hq');
            $table->string('doctor_name');
            $table->string('doctor_qualification');
            $table->string('doctor_phone');
            $table->string('doctor_photo')->nullable();
            $table->string('doctor_banner_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
