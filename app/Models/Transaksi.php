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
        'status_peminjaman',
        'approval_by',
        'approval_at',
        'keterangan_pengajuan',
        'alasan_penolakan',
        'status_mobil_keluar',
        'tanggal_mobil_keluar',
        'status_mobil_masuk',
        'tanggal_mobil_masuk',
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

    protected $casts = [
        'tanggal_mulai_sewa' => 'datetime',
        'tanggal_akhir_sewa' => 'datetime',
        'tanggal_kembali' => 'datetime',
        'approval_at' => 'datetime',
        'tanggal_mobil_keluar' => 'datetime',
        'tanggal_mobil_masuk' => 'datetime',
        'verifikasi_wajah_masuk_timestamp' => 'datetime',
        'verifikasi_wajah_keluar_timestamp' => 'datetime',
        'status_mobil_keluar' => 'boolean',
        'status_mobil_masuk' => 'boolean',
    ];

    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'mobil_id', 'mobil_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approval_by', 'user_id');
    }

    public function pemeriksaan()
    {
        return $this->hasOne(PemeriksaanKendaraan::class, 'transaksi_id', 'id_transaksi');
    }
}
