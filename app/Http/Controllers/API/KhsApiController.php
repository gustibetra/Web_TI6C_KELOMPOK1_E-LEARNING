<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Khs;
use Illuminate\Http\Request;

class KhsApiController extends Controller
{
    public function index() { return response()->json(Khs::with(['mahasiswa', 'matakuliah'])->get()); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'nilai_akhir' => 'nullable|numeric',
            'grade' => 'nullable|string',
            'ipk' => 'nullable|numeric',
        ]);
        return response()->json(Khs::create($data), 201);
    }

    public function show($id) { return response()->json(Khs::with(['mahasiswa', 'matakuliah'])->findOrFail($id)); }

    public function update(Request $request, $id)
    {
        $khs = Khs::findOrFail($id);
        $khs->update($request->all());
        return response()->json($khs);
    }

    public function destroy($id)
    {
        Khs::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}