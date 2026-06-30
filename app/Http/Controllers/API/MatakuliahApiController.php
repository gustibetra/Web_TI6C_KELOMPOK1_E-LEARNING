<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Matakuliah;
use Illuminate\Http\Request;

class MatakuliahApiController extends Controller
{
    public function index() { return response()->json(Matakuliah::with('dosen')->get()); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'kode_matkul' => 'required',
            'nama_matkul' => 'required',
            'sks' => 'required|integer',
            'semester' => 'required|integer',
        ]);
        return response()->json(Matakuliah::create($data), 201);
    }

    public function show($id) { return response()->json(Matakuliah::findOrFail($id)); }

    public function update(Request $request, $id)
    {
        $mk = Matakuliah::findOrFail($id);
        $mk->update($request->all());
        return response()->json($mk);
    }

    public function destroy($id)
    {
        Matakuliah::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}