<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'idkategori'; // Sesuai ERD Modul
    protected $fillable = ['nama_kategori']; // Kolom yang boleh diisi
}