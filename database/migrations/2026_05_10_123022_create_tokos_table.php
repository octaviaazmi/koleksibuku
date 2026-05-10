<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('toko', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique(); // ID Unik Toko
            $table->string('nama_toko');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('accuracy')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('toko');
    }
};