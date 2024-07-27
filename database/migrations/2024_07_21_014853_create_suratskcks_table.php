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
        Schema::create('suratskcks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daftarsurat_id');
            $table->string('keperluan');
            $table->timestamps();

            $table->foreign('daftarsurat_id')->references('id')->on('daftarsurats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suratskcks');
    }
};
