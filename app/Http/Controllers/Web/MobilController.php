<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function index()
    {
        $mobil = Mobil::all();
        return response()->json($mobil);
    }

    public function show($id)
    {
        $mobil = Mobil::findOrFail($id);
        return response()->json($mobil);
    }

    public function store(Request $request)
    {
        $request->validate([
            'plat' => 'required|max:10|unique:mobil',
            'merk' => 'required|max:30',
            'model' => 'required|max:30',
            'type' => 'nullable|max:60',
            'tahun' => 'required|integer',
            'warna' => 'required|max:30',
            'harga_sewa' => 'required|numeric',
            'status' => 'required|max:30'
        ]);

        $mobil = Mobil::create($request->all());
        return response()->json($mobil, 201);
    }
    public function available()
    {
        // Ambil data mobil dengan status selain 'onGoing' dan 'completed'
        $mobilAvailable = Mobil::whereNotIn('status', ['onGoing', 'approved', 'pending'])->get();

        return response()->json([
            'message' => 'List mobil available',
            'data' => $mobilAvailable,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'plat' => 'required|max:10|unique:mobil,plat,' . $id . ',mobil_id',
            'merk' => 'required|max:30',
            'model' => 'required|max:30',
            'type' => 'nullable|max:60',
            'tahun' => 'required|integer',
            'warna' => 'required|max:30',
            'harga_sewa' => 'required|numeric',
            'status' => 'required|max:30'
        ]);

        $mobil = Mobil::findOrFail($id);
        $mobil->update($request->all());

        return response()->json($mobil);
    }

    public function destroy($id)
    {
        $mobil = Mobil::findOrFail($id);
        $mobil->delete();

        return response()->json(null, 204);
    }
}
