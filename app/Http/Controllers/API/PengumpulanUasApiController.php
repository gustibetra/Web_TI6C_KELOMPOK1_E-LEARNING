<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanUas;
use Illuminate\Http\Request;

class PengumpulanUasApiController extends Controller
{
    public function index() { return response()->json(PengumpulanUas::with(['uas', 'mahasiswa'])->get()); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_uas' => 'required|exists:uas,id_uas',
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'link_pengumpulan_uas' => 'nullable|string',
            'kritik_dan_saran' => 'nullable|string',
        ]);
        
        if ($request->hasFile('jawaban')) {
            $path = $request->file('jawaban')->store('uploads/jawaban_uas', 'public');
            $data['link_pengumpulan_uas'] = $path;
        }
        
        return response()->json(PengumpulanUas::create($data), 201);
    }

    public function show($id) { return response()->json(PengumpulanUas::with(['uas', 'mahasiswa'])->findOrFail($id)); }

    public function update(Request $request, $id)
    {
        $pengumpulan = PengumpulanUas::findOrFail($id);
        
        if ($request->hasFile('jawaban')) {
            $path = $request->file('jawaban')->store('uploads/jawaban_uas', 'public');
            $request->merge(['link_pengumpulan_uas' => $path]);
        }
        
        $pengumpulan->update($request->all());
        return response()->json($pengumpulan);
    }

    public function destroy($id)
    {
        PengumpulanUas::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}