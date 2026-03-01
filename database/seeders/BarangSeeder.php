<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        // Kita HANYA memasukkan nama dan harga, id_barang akan diurus oleh Trigger!
        $dataUmkm = [
            ['nama_barang' => 'Keripik Singkong Pedas', 'harga' => 15000],
            ['nama_barang' => 'Kopi Robusta Bubuk 100gr', 'harga' => 25000],
            ['nama_barang' => 'Sambal Bawang Botolan', 'harga' => 20000],
            ['nama_barang' => 'Kue Kering Nastar', 'harga' => 45000],
            ['nama_barang' => 'Abon Sapi Asli', 'harga' => 35000],
            ['nama_barang' => 'Madu Hutan Liar 250ml', 'harga' => 55000],
            ['nama_barang' => 'Teh Melati Celup', 'harga' => 12000],
            ['nama_barang' => 'Bawang Goreng Renyah', 'harga' => 18000],
            ['nama_barang' => 'Krupuk Udang Mentah', 'harga' => 22000],
            ['nama_barang' => 'Sirup Tjampolay Rasa Pisang', 'harga' => 28000],
        ];

        foreach ($dataUmkm as $item) {
            Barang::create($item);
        }
    }
}