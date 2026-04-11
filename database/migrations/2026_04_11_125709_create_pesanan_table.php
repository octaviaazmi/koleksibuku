<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pesanan', function (Blueprint $table) {
            // Karena Midtrans butuh Order ID yang string unik, kita jadikan idpesanan ini string (misal: ORD-001)
            $table->string('idpesanan')->primary(); 
            $table->string('nama', 255); // Nama Guest otomatis
            $table->integer('total');
            $table->string('metode_bayar')->nullable(); 
            $table->string('status_bayar')->default('Pending'); // Defaultnya pending
            $table->string('snap_token')->nullable(); // INI TAMBAHAN UNTUK MIDTRANS
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pesanan');
    }
};