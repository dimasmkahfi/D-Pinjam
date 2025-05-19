<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PemeriksaanKendaraan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PemeriksaanController extends Controller
{
    public function antrianCek()
    {
        $pengajuans = Transaksi::with(['mobil', 'pemeriksaan'])
            ->whereHas('pemeriksaan', function ($query) {
                $query->where('status', 'menunggu');
            })
            ->orderBy('tanggal_mulai_sewa', 'asc')
            ->get();

        return view('pdi.antrian', compact('pengajuans'));
    }

    public function cekKendaraan($id)
    {
        $transaksi = Transaksi::with(['mobil', 'pemeriksaan'])->findOrFail($id);

        if (!$transaksi->pemeriksaan || $transaksi->pemeriksaan->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Pemeriksaan tidak dalam status menunggu');
        }

        return view('pdi.cek', compact('transaksi'));
    }

    public function simpanHasilCek(Request $request, $id)
    {
        $request->validate([
            'oli' => 'required|boolean',
            'tekanan_ban' => 'required|boolean',
            'tool_set' => 'required|boolean',
            'mesin' => 'required|boolean',
            'catatan' => 'nullable|string',
            'status' => 'required|in:lulus,tidak_lulus',
        ]);

        $transaksi = Transaksi::with('pemeriksaan')->findOrFail($id);

        if (!$transaksi->pemeriksaan || $transaksi->pemeriksaan->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Pemeriksaan tidak dalam status menunggu');
        }

        $pemeriksaan = $transaksi->pemeriksaan;
        $pemeriksaan->update([
            'petugas_id' => Auth::id(),
            'oli' => $request->oli,
            'tekanan_ban' => $request->tekanan_ban,
            'tool_set' => $request->tool_set,
            'mesin' => $request->mesin,
            'catatan' => $request->catatan,
            'status' => $request->status,
            'tanggal_pemeriksaan' => Carbon::now(),
        ]);

        // Update status transaksi jika tidak lulus pemeriksaan
        if ($request->status === 'tidak_lulus') {
            $transaksi->update([
                'status_peminjaman' => 'dibatalkan',
                'keterangan_pengajuan' => 'Dibatalkan karena tidak lulus pemeriksaan: ' . $request->catatan,
            ]);

            // Update status mobil menjadi tersedia kembali
            $transaksi->mobil()->update(['status' => 'Tersedia']);
        } else {
            $transaksi->update([
                'status_peminjaman' => 'berjalan',
            ]);
        }

        return redirect()->route('pdi.antrian')
            ->with('success', 'Hasil pemeriksaan berhasil disimpan');
    }

    public function riwayatPemeriksaan()
    {
        $pemeriksaans = PemeriksaanKendaraan::with(['transaksi', 'transaksi.mobil', 'petugas'])
            ->whereNotNull('tanggal_pemeriksaan')
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->get();

        return view('pdi.riwayat', compact('pemeriksaans'));
    }
}
