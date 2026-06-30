<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;

class PengumpulanTugasApiController extends Controller
{
    public function index() { return response()->json(PengumpulanTugas::with(['tugas', 'mahasiswa'])->get()); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_tugas' => 'required|exists:tugas,id_tugas',
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'link_pengumpulan_tugas' => 'nullable|string',
        ]);
        
        if ($request->hasFile('jawaban')) {
            $path = $request->file('jawaban')->store('uploads/jawaban_tugas', 'public');
            $data['link_pengumpulan_tugas'] = $path;
        }
        
        return response()->json(PengumpulanTugas::create($data), 201);
    }

    public function show($id) { return response()->json(PengumpulanTugas::with(['tugas', 'mahasiswa'])->findOrFail($id)); }

    public function update(Request $request, $id)
    {
        $pengumpulan = PengumpulanTugas::findOrFail($id);
        
        if ($request->hasFile('jawaban')) {
            $path = $request->file('jawaban')->store('uploads/jawaban_tugas', 'public');
            $request->merge(['link_pengumpulan_tugas' => $path]);
        }
        
        $pengumpulan->update($request->all());
        return response()->json($pengumpulan);
    }

    public function destroy($id)
    {
        PengumpulanTugas::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}