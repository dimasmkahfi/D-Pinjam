<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Mobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function index()
    {
        $mobil = Mobil::all();
        return view('admin.mobil.index', compact('mobil'));
    }

    public function create()
    {
        return view('admin.mobil.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'plat' => 'required|max:10|unique:mobil,plat',
            'merk' => 'required|max:30',
            'model' => 'required|max:30',
            'type' => 'nullable|max:60',
            'tahun' => 'required|integer',
            'warna' => 'required|max:30',
            'harga_sewa' => 'required|numeric',
            'status' => 'required|max:30'
        ]);

        Mobil::create($request->all());

        return redirect()->route('admin.mobil.index')->with('success', 'Data mobil berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mobil = Mobil::findOrFail($id);
        return view('admin.mobil.edit', compact('mobil'));
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

        return redirect()->route('admin.mobil.index')->with('success', 'Data mobil berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mobil = Mobil::findOrFail($id);
        $mobil->delete();

        return redirect()->route('admin.mobil.index')->with('success', 'Data mobil berhasil dihapus.');
    }

    public function report()
    {
        $mobil = Mobil::all();
        return view('kepalabengkel.report', compact('mobil'));
    }
}
