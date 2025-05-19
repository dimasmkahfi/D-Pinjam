<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use Illuminate\Http\Request;

class AddOnController extends Controller
{
    public function index()
    {
        $addOns = AddOn::all();
        return response()->json($addOns);
    }

    public function show($id)
    {
        $addOn = AddOn::findOrFail($id);
        return response()->json($addOn);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tambahan' => 'required|max:60',
            'harga' => 'required|integer'
        ]);

        $addOn = AddOn::create($request->all());
        return response()->json($addOn, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_tambahan' => 'required|max:60',
            'harga' => 'required|integer'
        ]);

        $addOn = AddOn::findOrFail($id);
        $addOn->update($request->all());

        return response()->json($addOn);
    }

    public function destroy($id)
    {
        $addOn = AddOn::findOrFail($id);
        $addOn->delete();

        return response()->json(null, 204);
    }
}
