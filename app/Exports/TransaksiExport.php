<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Transaksi::with(['mobil', 'approver'])->get();
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Nama Penyewa',
            'Merk Mobil',
            'Model Mobil',
            'Plat Nomor',
            'Tanggal Mulai',
            'Tanggal Akhir',
            'Tanggal Kembali',
            'Status',
            'Disetujui Oleh',
            'Tanggal Persetujuan',
            'Total Biaya',
            'Denda',
            'Tambahan Sewa',
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->id_transaksi,
            $transaksi->nama_penyewa,
            $transaksi->mobil->merk ?? 'N/A',
            $transaksi->mobil->model ?? 'N/A',
            $transaksi->mobil->plat ?? 'N/A',
            $transaksi->tanggal_mulai_sewa,
            $transaksi->tanggal_akhir_sewa,
            $transaksi->tanggal_kembali,
            $transaksi->status_peminjaman,
            $transaksi->approver->username ?? 'N/A',
            $transaksi->approval_at,
            $transaksi->total_biaya,
            $transaksi->denda,
            $transaksi->tambahan_sewa,
        ];
    }
}
