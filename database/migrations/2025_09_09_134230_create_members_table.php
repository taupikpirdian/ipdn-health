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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->string('name');
            $table->string('jk');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('district_id')->nullable();
            $table->string('no_kesehatan_tahap1')->nullable();
            $table->string('no_kesehatan_tahap2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
