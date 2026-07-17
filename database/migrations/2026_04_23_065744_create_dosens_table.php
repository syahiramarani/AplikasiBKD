<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();

            $table->string('nidn')->unique();

            $table->string('nama');

            $table->enum('status', ['DT', 'DS']);

            $table->foreignId('jurusan_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('prodi_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('kategori_mengajar')->nullable();

            $table->integer('sks')->nullable();

            $table->integer('jam')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
