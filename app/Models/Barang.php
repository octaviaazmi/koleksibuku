<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    
    // ngasitau kalo pk nya custom
    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    protected $keyType = 'string';

    // kolom yg bisa diisi manual
    protected $fillable = ['nama_barang', 'harga'];
}