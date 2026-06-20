<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('employee_name')->nullable()->change();
            $table->string('employee_hq')->nullable()->change();
            $table->string('doctor_qualification')->nullable()->change();
            $table->string('doctor_phone')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('employee_name')->nullable(false)->change();
            $table->string('employee_hq')->nullable(false)->change();
            $table->string('doctor_qualification')->nullable(false)->change();
            $table->string('doctor_phone')->nullable(false)->change();
        });
    }
};
