<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Mobil;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{

    public function stores(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'mobil_id' => 'required|exists:mobil,mobil_id',
                'nama_penyewa' => 'required|max:30',
                'tanggal_mulai_sewa' => 'required|date',
                'tanggal_akhir_sewa' => 'required|date|after:tanggal_mulai_sewa',
            ]);

            // Cek apakah pengguna sudah pernah mengajukan sewa untuk mobil yang sama
            $existingTransaksi = Transaksi::where('mobil_id', $validated['mobil_id'])
                ->where('nama_penyewa', $validated['nama_penyewa'])
                ->whereIn('status', ['pending', 'onGoing', 'approved'])
                ->exists();

            if ($existingTransaksi) {
                return response()->json([
                    'message' => 'Pengguna sudah memiliki transaksi yang sedang diproses atau sedang aktif.',
                ], 200); // Mengembalikan status 400 jika sudah ada transaksi
            }

            // Default status untuk transaksi baru adalah pending
            $validated['status'] = 'pending';

            // Update status mobil menjadi pending
            $mobil = Mobil::findOrFail($validated['mobil_id']);
            $mobil->status = 'pending';
            $mobil->save();

            // Membuat transaksi baru
            $transaksi = Transaksi::create($validated);

            // Kembalikan response sukses
            return response()->json([
                'message' => 'Transaksi berhasil disimpan.',
                'data' => $transaksi
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function mobilKeluar(Request $request, $id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            $mobil = Mobil::findOrFail($transaksi->mobil_id);

            // Validasi input foto dan confidence
            $request->validate([]);

            // Simpan foto verifikasi wajah keluar
            if (!$request->hasFile('foto')) {
            } else {
                $fotoPath = $request->file('foto')->store('verifikasi_wajah_keluar', 'public');
            }

            // Update status mobil jadi onGoing
            $mobil->status = 'onGoing';
            $mobil->save();

            // Update data transaksi untuk verifikasi wajah keluar
            $transaksi->verifikasi_wajah_keluar_status = 1;
            $transaksi->verifikasi_wajah_keluar_timestamp = now();
            $transaksi->verifikasi_wajah_keluar_foto = $fotoPath ?? null;
            $transaksi->verifikasi_wajah_keluar_confidence = $request->confidence ?? null;
            $transaksi->status = 'onGoing';
            $transaksi->save();

            return response()->json([
                'message' => 'Mobil keluar dan verifikasi wajah berhasil disimpan',
                'transaksi' => $transaksi,
                'mobil' => $mobil,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memproses mobil keluar',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function mobilMasuk(Request $request, $id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            $mobil = Mobil::findOrFail($transaksi->mobil_id);

            // Validasi input foto dan confidence jika perlu
            $request->validate([]);

            // Simpan foto verifikasi wajah masuk jika ada
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('verifikasi_wajah_masuk', 'public');
            } else {
                $fotoPath = null;
            }

            // Update status mobil jadi 'completed' (atau sesuai flow kamu)
            $mobil->status = 'completed';
            $mobil->save();

            // Update data transaksi untuk verifikasi wajah masuk
            $transaksi->verifikasi_wajah_masuk_status = 1;
            $transaksi->verifikasi_wajah_masuk_timestamp = now();
            $transaksi->verifikasi_wajah_masuk_foto = $fotoPath;
            $transaksi->verifikasi_wajah_masuk_confidence = $request->confidence ?? null;

            // Update status transaksi
            $transaksi->status = 'completed';
            $transaksi->save();

            return response()->json([
                'message' => 'Mobil masuk dan verifikasi wajah berhasil disimpan',
                'transaksi' => $transaksi,
                'mobil' => $mobil,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memproses mobil masuk',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cancelTransaksi(Request $request, $id)
    {
        try {
            // Cari transaksi berdasarkan ID
            $transaksi = Transaksi::findOrFail($id);

            // Validasi jika status transaksi tidak bisa dibatalkan
            if ($transaksi->status == 'completed' || $transaksi->status == 'cancelled' || $transaksi->status == 'onGoing') {
                return response()->json([
                    'message' => 'Transaksi sudah selesai atau dibatalkan, tidak dapat dibatalkan lagi.'
                ], 400);
            }

            // Update status transaksi menjadi cancelled
            $transaksi->status = 'cancelled';
            $transaksi->save();

            // Update status mobil menjadi 'available' kembali jika perlu
            $mobil = Mobil::findOrFail($transaksi->mobil_id);
            $mobil->status = 'cancelled';
            $mobil->save();

            // Kembalikan response sukses
            return response()->json([
                'message' => 'Transaksi berhasil dibatalkan.',
                'transaksi' => $transaksi,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membatalkan transaksi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        $transaksi = Transaksi::with('mobil')
            ->orderByRaw("FIELD(status, 'onGoing', 'approved', 'pending')") // Mengurutkan 'onGoing' dulu, lalu 'approved', dan 'pending'
            ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan tanggal dibuat, 'desc' untuk urutan terbaru
            ->get();

        return response()->json($transaksi);
    }


    public function listTransaksiSatpam()
    {
        $transaksi = Transaksi::with('mobil')
            ->orderByRaw("FIELD(status, 'onGoing', 'approved', 'pending')") // Sorting: onGoing -> approved -> pending
            ->get();

        return response()->json($transaksi);
    }

    public function independent_transaksi_list($penyewa)
    {
        $transaksi = Transaksi::with('mobil')
            ->orderBy('id_transaksi', 'desc') // Mengurutkan berdasarkan tanggal dibuat, 'desc' untuk urutan terbaru
            ->get()->where('nama_penyewa', $penyewa);
        return response()->json($transaksi);
    }


    public function show($id)
    {
        $transaksi = Transaksi::with('mobil')->findOrFail($id);
        return response()->json($transaksi);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'mobil_id' => 'exists:mobil,mobil_id',
            'nama_penyewa' => 'max:30',
            'tanggal_mulai_sewa' => 'date',
            'tanggal_akhir_sewa' => 'date|after:tanggal_mulai_sewa',
            'total_biaya' => 'numeric'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update($request->all());

        return response()->json($transaksi);
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Set status mobil menjadi tersedia
        $mobil = Mobil::findOrFail($transaksi->mobil_id);
        $mobil->status = 'Tersedia';
        $mobil->save();

        $transaksi->delete();

        return response()->json(null, 204);
    }

    public function kembalikan(Request $request, $id)
    {
        $request->validate([
            'tanggal_kembali' => 'required|date',
            'kerusakan' => 'nullable|string',
            'denda' => 'nullable|numeric'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->tanggal_kembali = $request->tanggal_kembali;
        $transaksi->kerusakan = $request->kerusakan ?? '';
        $transaksi->denda = $request->denda ?? 0;
        $transaksi->save();

        // Set status mobil menjadi tersedia
        $mobil = Mobil::findOrFail($transaksi->mobil_id);
        $mobil->status = 'Tersedia';
        $mobil->save();

        return response()->json($transaksi);
    }

    public function verifikasiWajahMasuk(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
            'foto' => 'nullable|string',
            'confidence' => 'nullable|numeric',
            'keterangan' => 'nullable|string'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->verifikasi_wajah_masuk_status = $request->status;
        $transaksi->verifikasi_wajah_masuk_timestamp = now();
        $transaksi->verifikasi_wajah_masuk_foto = $request->foto;
        $transaksi->verifikasi_wajah_masuk_confidence = $request->confidence;
        $transaksi->verifikasi_wajah_keterangan = $request->keterangan;
        $transaksi->save();

        return response()->json($transaksi);
    }

    public function verifikasiWajahKeluar(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
            'foto' => 'nullable|string',
            'confidence' => 'nullable|numeric',
            'keterangan' => 'nullable|string'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->verifikasi_wajah_keluar_status = $request->status;
        $transaksi->verifikasi_wajah_keluar_timestamp = now();
        $transaksi->verifikasi_wajah_keluar_foto = $request->foto;
        $transaksi->verifikasi_wajah_keluar_confidence = $request->confidence;
        $transaksi->verifikasi_wajah_keterangan = $request->keterangan;
        $transaksi->save();

        return response()->json($transaksi);
    }
}
