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
        Schema::create('medical_check_ups', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->string('klasifikasi');
            $table->string('hal');
            $table->string('hal_khusus');
            $table->string('nilai');
            $table->text('saran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_check_ups');
    }
};