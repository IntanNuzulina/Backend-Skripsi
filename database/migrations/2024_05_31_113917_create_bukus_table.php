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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kategori')->constrained('kategoris')->onDelete('cascade');
            $table->unsignedBigInteger('id_flash_sales')->nullable()->default(NULL);
            $table->string('penerbit');
            $table->string('judul');
            $table->string('penulis');
            $table->string('gambar')->nullable();
            $table->string('harga');
            $table->string('deskripsi')->nullable();
            $table->integer('stok');
            $table->integer('halaman');
            $table->string('thn_terbit');
            $table->string('bahasa');
            $table->string('isbn');
            $table->foreign('id_flash_sales')->references('id')->on('flash_sales')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
