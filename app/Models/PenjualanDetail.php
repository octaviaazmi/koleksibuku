<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    protected $table = 'penjualan_detail';
    public $timestamps = false; // Karena di tabel ini kita tidak butuh created_at / updated_at
    protected $fillable = ['id_penjualan', 'id_barang', 'harga', 'jumlah', 'subtotal'];
}