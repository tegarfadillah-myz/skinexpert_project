<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('sender_id');
            $table->enum('sender_type', ['user','dokter']);
            $table->text('body');              
            $table->boolean('is_read')->default(false);

            $table->timestamps();

            $table->index(['consultation_id','created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

