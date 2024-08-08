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
        Schema::create('domisililuars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daftarsurat_id');
            $table->string('perihal');
            $table->string('tempat_lahir_pemohon')->nullable();
            $table->date('tanggal_lahir_pemohon')->nullable();
            $table->string('jenis_kelamin_pemohon')->nullable();
            $table->string('agama_pemohon')->nullable();
            $table->string('kewarganegaraan_pemohon')->nullable();
            $table->string('pekerjaan_pemohon')->nullable();
            $table->string('status_perkawinan_pemohon')->nullable();
            $table->string('pendidikan_pemohon')->nullable();
            $table->string('alamat_pemohon')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('keperluan')->nullable();
            $table->timestamps();

            $table->foreign('daftarsurat_id')->references('id')->on('daftarsurats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domisililuars');
    }
};
