<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $transaksis = [
            [
                'mobil_id' => 1,
                'nama_penyewa' => 'John Doe',
                'tanggal_mulai_sewa' => $now->copy()->addDays(1),
                'tanggal_akhir_sewa' => $now->copy()->addDays(5),
                'tanggal_kembali' => null,
                'tambahan_sewa' => null,
                'kerusakan' => null,
                'total_biaya' => 1500000,
                'denda' => null,
                'status_peminjaman' => 'pengajuan',
                'approval_by' => null,
                'approval_at' => null,
                'keterangan_pengajuan' => 'Perjalanan dinas.',
                'alasan_penolakan' => null,
                'status_mobil_keluar' => false,
                'tanggal_mobil_keluar' => null,
                'status_mobil_masuk' => false,
                'tanggal_mobil_masuk' => null,
                'verifikasi_wajah_masuk_status' => null,
                'verifikasi_wajah_masuk_timestamp' => null,
                'verifikasi_wajah_masuk_foto' => null,
                'verifikasi_wajah_masuk_confidence' => null,
                'verifikasi_wajah_keluar_status' => null,
                'verifikasi_wajah_keluar_timestamp' => null,
                'verifikasi_wajah_keluar_foto' => null,
                'verifikasi_wajah_keluar_confidence' => null,
                'verifikasi_wajah_keterangan' => null,
            ],
            [
                'mobil_id' => 2,
                'nama_penyewa' => 'Jane Smith',
                'tanggal_mulai_sewa' => $now->copy()->subDays(3),
                'tanggal_akhir_sewa' => $now->copy()->addDays(2),
                'tanggal_kembali' => null,
                'tambahan_sewa' => 500000,
                'kerusakan' => 'Lecet di bagian bumper belakang.',
                'total_biaya' => 2000000,
                'denda' => 150000,
                'status_peminjaman' => 'berjalan',
                'approval_by' => 1,
                'approval_at' => $now->copy()->subDays(4),
                'keterangan_pengajuan' => 'Untuk keperluan keluarga.',
                'alasan_penolakan' => null,
                'status_mobil_keluar' => true,
                'tanggal_mobil_keluar' => $now->copy()->subDays(3),
                'status_mobil_masuk' => false,
                'tanggal_mobil_masuk' => null,
                'verifikasi_wajah_masuk_status' => 'verified',
                'verifikasi_wajah_masuk_timestamp' => $now->copy()->subDays(3),
                'verifikasi_wajah_masuk_foto' => 'path/to/foto1.jpg',
                'verifikasi_wajah_masuk_confidence' => 0.94,
                'verifikasi_wajah_keluar_status' => null,
                'verifikasi_wajah_keluar_timestamp' => null,
                'verifikasi_wajah_keluar_foto' => null,
                'verifikasi_wajah_keluar_confidence' => null,
                'verifikasi_wajah_keterangan' => null,
            ]
        ];

        DB::table('transaksi')->insert($transaksis);
    }
}
