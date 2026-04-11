<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model {
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    public $incrementing = false; 
    protected $keyType = 'string';
    protected $fillable = ['idpesanan', 'nama', 'total', 'metode_bayar', 'status_bayar', 'snap_token'];

    public function detail() {
        return $this->hasMany(DetailPesanan::class, 'idpesanan');
    }
}