<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat Tabel Barang
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id_barang', 10)->primary(); // Primary key custom
            $table->string('nama_barang');
            $table->integer('harga');
            $table->timestamps();
        });

        // 2. Buat Trigger Penomoran Otomatis (BRG001, BRG002, dst)
        DB::unprepared("
            CREATE TRIGGER tr_generate_id_barang BEFORE INSERT ON barang
            FOR EACH ROW
            BEGIN
                DECLARE last_id INT;
                
                -- Cari angka terakhir dari id_barang yang sudah ada
                SELECT MAX(CAST(SUBSTRING(id_barang, 4) AS UNSIGNED)) INTO last_id FROM barang;
                
                -- Kalau tabel masih kosong, mulai dari BRG001
                IF last_id IS NULL THEN
                    SET NEW.id_barang = 'BRG001';
                ELSE
                -- Kalau sudah ada isi, angka terakhir ditambah 1
                    SET NEW.id_barang = CONCAT('BRG', LPAD(last_id + 1, 3, '0'));
                END IF;
            END
        ");
    }

    public function down(): void
    {
        // Hapus trigger dulu baru hapus tabel kalau di-rollback
        DB::unprepared("DROP TRIGGER IF EXISTS tr_generate_id_barang");
        Schema::dropIfExists('barang');
    }
};
