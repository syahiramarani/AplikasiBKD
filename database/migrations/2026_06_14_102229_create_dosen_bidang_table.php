<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('dosen_bidang', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('dosen_id');
            $table->unsignedBigInteger('bidang_id');

            $table->foreign('dosen_id')
                ->references('id')
                ->on('dosens')
                ->onDelete('cascade');

            $table->foreign('bidang_id')
                ->references('id')
                ->on('bidangs')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dosen_bidang');
    }
};