<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;

    protected $table = 'mobil';
    protected $primaryKey = 'mobil_id';
    public $timestamps = false;

    protected $fillable = [
        'plat',
        'merk',
        'model',
        'type',
        'tahun',
        'warna',
        'harga_sewa',
        'status'
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'mobil_id', 'mobil_id');
    }
}
