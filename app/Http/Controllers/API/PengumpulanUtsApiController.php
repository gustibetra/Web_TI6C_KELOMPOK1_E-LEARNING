<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanUts;
use Illuminate\Http\Request;

class PengumpulanUtsApiController extends Controller
{
    public function index() { return response()->json(PengumpulanUts::with(['uts', 'mahasiswa'])->get()); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_uts' => 'required|exists:uts,id_uts',
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'link_pengumpulan_uts' => 'nullable|string',
        ]);
        
        if ($request->hasFile('jawaban')) {
            $path = $request->file('jawaban')->store('uploads/jawaban_uts', 'public');
            $data['link_pengumpulan_uts'] = $path;
        }
        
        return response()->json(PengumpulanUts::create($data), 201);
    }

    public function show($id) { return response()->json(PengumpulanUts::with(['uts', 'mahasiswa'])->findOrFail($id)); }

    public function update(Request $request, $id)
    {
        $pengumpulan = PengumpulanUts::findOrFail($id);
        
        if ($request->hasFile('jawaban')) {
            $path = $request->file('jawaban')->store('uploads/jawaban_uts', 'public');
            $request->merge(['link_pengumpulan_uts' => $path]);
        }
        
        $pengumpulan->update($request->all());
        return response()->json($pengumpulan);
    }

    public function destroy($id)
    {
        PengumpulanUts::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}