<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false;

    protected $fillable = [
        'mobil_id',
        'nama_penyewa',
        'tanggal_mulai_sewa',
        'tanggal_akhir_sewa',
        'tanggal_kembali',
        'tambahan_sewa',
        'kerusakan',
        'total_biaya',
        'denda',
        'verifikasi_wajah_masuk_status',
        'verifikasi_wajah_masuk_timestamp',
        'verifikasi_wajah_masuk_foto',
        'verifikasi_wajah_masuk_confidence',
        'verifikasi_wajah_keluar_status',
        'verifikasi_wajah_keluar_timestamp',
        'verifikasi_wajah_keluar_foto',
        'verifikasi_wajah_keluar_confidence',
        'verifikasi_wajah_keterangan'
    ];

    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'mobil_id', 'mobil_id');
    }
}
