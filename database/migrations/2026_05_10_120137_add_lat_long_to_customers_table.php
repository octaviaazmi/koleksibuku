<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customer', function (Blueprint $table) {
            // Menambahkan kolom latitude dan longitude (bisa disesuaikan posisinya)
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
        });
    }

    public function down()
    {
        Schema::table('customer', function (Blueprint $table) {
            // Untuk menghapus kolom jika di-rollback
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};