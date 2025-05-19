<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanKendaraan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_kendaraan';

    protected $fillable = [
        'transaksi_id',
        'petugas_id',
        'oli',
        'tekanan_ban',
        'tool_set',
        'mesin',
        'catatan',
        'status',
        'tanggal_pemeriksaan'
    ];

    protected $casts = [
        'oli' => 'boolean',
        'tekanan_ban' => 'boolean',
        'tool_set' => 'boolean',
        'mesin' => 'boolean',
        'tanggal_pemeriksaan' => 'datetime',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'id_transaksi');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id', 'user_id');
    }
}
