<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SatpamController extends Controller
{
    public function listTransaksi()
    {
        $aktifTransaksis = Transaksi::with(['mobil', 'pemeriksaan'])
            ->where('status_peminjaman', 'berjalan')
            ->where(function ($query) {
                $query->where('status_mobil_keluar', false)
                    ->orWhere(function ($q) {
                        $q->where('status_mobil_keluar', true)
                            ->where('status_mobil_masuk', false);
                    });
            })
            ->orderBy('tanggal_mulai_sewa', 'asc')
            ->get();

        return view('satpam.list', compact('aktifTransaksis'));
    }

    public function mobilKeluar($id)
    {
        $transaksi = Transaksi::with(['mobil', 'pemeriksaan'])->findOrFail($id);

        if ($transaksi->status_peminjaman !== 'berjalan' || $transaksi->status_mobil_keluar) {
            return redirect()->back()->with('error', 'Status transaksi tidak valid untuk pengeluaran mobil');
        }

        $transaksi->update([
            'status_mobil_keluar' => true,
            'tanggal_mobil_keluar' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Status mobil keluar berhasil diperbarui');
    }

    public function mobilMasuk($id)
    {
        $transaksi = Transaksi::with(['mobil', 'pemeriksaan'])->findOrFail($id);

        if ($transaksi->status_peminjaman !== 'berjalan' || !$transaksi->status_mobil_keluar || $transaksi->status_mobil_masuk) {
            return redirect()->back()->with('error', 'Status transaksi tidak valid untuk penerimaan mobil');
        }

        $transaksi->update([
            'status_mobil_masuk' => true,
            'tanggal_mobil_masuk' => Carbon::now(),
            'status_peminjaman' => 'selesai',
            'tanggal_kembali' => Carbon::now(),
        ]);

        // Update status mobil menjadi tersedia kembali
        $transaksi->mobil()->update(['status' => 'Tersedia']);

        return redirect()->back()->with('success', 'Status mobil masuk berhasil diperbarui dan transaksi selesai');
    }
}
