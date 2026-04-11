<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KantinSeeder extends Seeder {
    public function run(): void {
        // 1. Masukin 2 Penjual (Vendor)
        DB::table('vendor')->insert([
            ['nama_vendor' => 'Kantin Teknik (Bu Siti)'],
            ['nama_vendor' => 'Warkop Informatika']
        ]);

        // 2. Masukin Menu makanannya
        DB::table('menu')->insert([
            ['idvendor' => 1, 'nama_menu' => 'Nasi Goreng Spesial', 'harga' => 15000],
            ['idvendor' => 1, 'nama_menu' => 'Es Teh Manis', 'harga' => 4000],
            ['idvendor' => 2, 'nama_menu' => 'Ayam Geprek Level 5', 'harga' => 13000],
            ['idvendor' => 2, 'nama_menu' => 'Kopi Hitam', 'harga' => 5000],
        ]);
    }
}