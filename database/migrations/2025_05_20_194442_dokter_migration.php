<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokter', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokter');
            $table->integer('harga_konsultasi');
            $table->integer('tahun_pengalaman');
            $table->string('kota');
            $table->decimal('rating', 5, 2)->default(0);
            $table->string('spesialisasi')->nullable();
            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('email_dokter')->unique()->nullable();
            $table->string('nohp_dokter')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};
