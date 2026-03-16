<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('id_penjualan'); // Primary Key
            $table->integer('total');
            $table->timestamps(); // Otomatis bikin created_at sebagai timestamp transaksi
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};