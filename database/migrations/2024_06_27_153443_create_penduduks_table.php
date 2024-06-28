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
        Schema::create('penduduks', function (Blueprint $table) {
            $table->string('NIK', 16)->primary();
            $table->string('nama_depan');
            $table->string('nama_belakang');
            $table->date('tanggal_lahir');
            $table->string('desa');
            $table->integer('RT');
            $table->integer('RW');
            $table->enum('status', ['menetap','pindah', 'meninggal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduks');
    }
};
