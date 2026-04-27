<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customer', function (Blueprint $table) {
            $table->id('idcustomer');
            $table->string('nama', 255);
            // Kolom untuk nyimpen BLOB (Base64 Teks Panjang)
            $table->longText('foto_blob')->nullable(); 
            // Kolom untuk nyimpen Path File Fisik (Storage)
            $table->string('foto_path', 255)->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('customer');
    }
};