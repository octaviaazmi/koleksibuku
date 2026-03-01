<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    
    // Kasih tahu Laravel kalau primary key-nya custom
    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    protected $keyType = 'string';

    // Kolom yang boleh diisi manual (id_barang nggak usah, karena diisi Trigger)
    protected $fillable = ['nama_barang', 'harga'];
}