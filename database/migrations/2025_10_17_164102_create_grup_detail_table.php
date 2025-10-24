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
        Schema::create('grup_detail', function (Blueprint $table) {
            $table->id();
            $table->string('kode_grup', 3);
            $table->string('nik', 10);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('kode_grup')->references('kode_grup')->on('grup')->onDelete('cascade');
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade');

            // Unique constraint untuk mencegah duplikasi
            $table->unique(['kode_grup', 'nik']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grup_detail');
    }
};
