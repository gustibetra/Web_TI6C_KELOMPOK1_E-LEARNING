<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use Illuminate\Http\Request;

class KrsApiController extends Controller
{
    public function index() { return response()->json(Krs::with(['mahasiswa', 'matakuliah.dosen'])->get()); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'semester' => 'required|integer',
        ]);
        return response()->json(Krs::create($data), 201);
    }

    public function show($id) { return response()->json(Krs::with(['mahasiswa', 'matakuliah'])->findOrFail($id)); }

    public function update(Request $request, $id)
    {
        $krs = Krs::findOrFail($id);
        $krs->update($request->all());
        return response()->json($krs);
    }

    public function destroy($id)
    {
        Krs::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}