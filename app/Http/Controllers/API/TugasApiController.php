<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasApiController extends Controller
{
    public function index() { return response()->json(Tugas::with(['matakuliah', 'dosen'])->get()); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'deadline' => 'required|string',
        ]);
        
        if ($request->hasFile('soal')) {
            $path = $request->file('soal')->store('uploads/tugas', 'public');
            $data['soal_tugas'] = $path;
        }
        
        return response()->json(Tugas::create($data), 201);
    }

    public function show($id) { return response()->json(Tugas::with(['matakuliah', 'dosen', 'pengumpulan'])->findOrFail($id)); }

    public function update(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);
        
        if ($request->hasFile('soal')) {
            $path = $request->file('soal')->store('uploads/tugas', 'public');
            $request->merge(['soal_tugas' => $path]);
        }
        
        $tugas->update($request->all());
        return response()->json($tugas);
    }

    public function destroy($id)
    {
        Tugas::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}