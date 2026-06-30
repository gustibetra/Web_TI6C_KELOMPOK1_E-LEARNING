<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use Illuminate\Http\Request;

class AbsenApiController extends Controller
{
    public function index() { return response()->json(Absen::with(['mahasiswa', 'matakuliah'])->get()); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'tanggal_absen' => 'nullable|date',
            'status' => 'required|in:hadir,tidak_hadir,pending',
        ]);
        
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('uploads/absen', 'public');
            $data['foto_pash'] = $path;
        }
        
        $data['tanggal_absen'] = $data['tanggal_absen'] ?? now();
        return response()->json(Absen::create($data), 201);
    }

    public function show($id) { return response()->json(Absen::with(['mahasiswa', 'matakuliah'])->findOrFail($id)); }

    public function update(Request $request, $id)
    {
        $absen = Absen::findOrFail($id);
        
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('uploads/absen', 'public');
            $request->merge(['foto_pash' => $path]);
        }
        
        $absen->update($request->all());
        return response()->json($absen);
    }

    public function destroy($id)
    {
        Absen::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}