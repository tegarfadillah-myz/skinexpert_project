<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()    
                  ->cascadeOnDelete();
            $table->foreignId('dokter_id')
                  ->constrained('dokter')
                  ->cascadeOnDelete();
            $table->enum('status', ['open','closed'])
                  ->default('open');
            $table->timestamps();

            $table->unique(['user_id','dokter_id','status'],
                           'uniq_active_consultation')
                  ->where('status', 'open');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};

