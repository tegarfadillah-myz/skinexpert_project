<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');                       // PK
            $table->string('nama_produk');
            $table->string('slug')->unique();              // ← kolom slug unik
            $table->text('deskripsi_produk')->nullable();
            $table->decimal('harga', 10, 2);
            $table->integer('stok')->default(0);
            $table->string('gambar_produk')->nullable();
            $table->string('nama_toko');
            $table->enum('kategori', [
                'mustraizer',
                'fashwas',
                'serum',
                'sunscreen',
                'produk lainnya',
            ]);
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
