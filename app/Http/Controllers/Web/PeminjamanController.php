<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PDF;
use App\Exports\TransaksiExport;
use Maatwebsite\Excel\Facades\Excel;

class PeminjamanController extends Controller
{
    public function antrianPengajuan()
    {
        $pengajuans = Transaksi::with('mobil')
            ->where('status_peminjaman', 'pengajuan')
            ->orderBy('tanggal_mulai_sewa', 'asc')
            ->get();

        return view('kepalabengkel.pengajuan', compact('pengajuans'));
    }

    public function setujuPengajuan(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status_peminjaman !== 'pengajuan') {
            return redirect()->back()->with('error', 'Status pengajuan sudah berubah');
        }

        $transaksi->update([
            'status_peminjaman' => 'disetujui',
            'approval_by' => Auth::id(),
            'approval_at' => Carbon::now(),
        ]);

        // Update status mobil menjadi 'Disewa'
        $transaksi->mobil()->update(['status' => 'Disewa']);

        // Buat entri pemeriksaan kendaraan
        \App\Models\PemeriksaanKendaraan::create([
            'transaksi_id' => $transaksi->id_transaksi,
            'status' => 'menunggu',
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui');
    }

    public function tolakPengajuan(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status_peminjaman !== 'pengajuan') {
            return redirect()->back()->with('error', 'Status pengajuan sudah berubah');
        }

        $transaksi->update([
            'status_peminjaman' => 'ditolak',
            'approval_by' => Auth::id(),
            'approval_at' => Carbon::now(),
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak');
    }

    public function statusPeminjaman()
    {
        $transaksis = Transaksi::with(['mobil', 'approver', 'pemeriksaan'])
            ->whereIn('status_peminjaman', ['disetujui', 'berjalan', 'selesai'])
            ->orderBy('tanggal_mulai_sewa', 'desc')
            ->get();

        return view('kepalabengkel.status', compact('transaksis'));
    }

    public function grafikKendaraan()
    {
        // Data untuk grafik penggunaan kendaraan per bulan
        $bulanIni = Carbon::now()->startOfMonth();
        $dataPerBulan = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = (clone $bulanIni)->subMonths($i);
            $namaBulan = $bulan->format('M Y');
            $jumlahTransaksi = Transaksi::whereYear('tanggal_mulai_sewa', $bulan->year)
                ->whereMonth('tanggal_mulai_sewa', $bulan->month)
                ->count();

            $dataPerBulan[$namaBulan] = $jumlahTransaksi;
        }

        // Data untuk grafik penggunaan per merk mobil
        $dataMerk = Transaksi::with('mobil')
            ->whereNotNull('mobil_id')
            ->whereIn('status_peminjaman', ['disetujui', 'berjalan', 'selesai'])
            ->get()
            ->groupBy('mobil.merk')
            ->map(function ($item) {
                return $item->count();
            });

        return view('kepalabengkel.grafik', compact('dataPerBulan', 'dataMerk'));
    }

    public function reportPeminjaman()
    {
        $transaksis = Transaksi::with(['mobil', 'approver', 'pemeriksaan'])
            ->orderBy('tanggal_mulai_sewa', 'desc')
            ->get();

        return view('kepalabengkel.report', compact('transaksis'));
    }

    public function exportPDF()
    {
        $transaksis = Transaksi::with(['mobil', 'approver', 'pemeriksaan'])
            ->orderBy('tanggal_mulai_sewa', 'desc')
            ->get();

        $pdf = PDF::loadView('kepalabengkel.export.pdf', compact('transaksis'));

        return $pdf->download('laporan-peminjaman-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new TransaksiExport, 'laporan-peminjaman-' . date('Y-m-d') . '.xlsx');
    }
}
