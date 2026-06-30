<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Uas;
use Illuminate\Http\Request;

class UasApiController extends Controller
{
    public function index() { return response()->json(Uas::with(['matakuliah', 'dosen'])->get()); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'deadline' => 'required|string',
        ]);
        
        if ($request->hasFile('soal')) {
            $path = $request->file('soal')->store('uploads/uas', 'public');
            $data['soal_uas'] = $path;
        }
        
        return response()->json(Uas::create($data), 201);
    }

    public function show($id) { return response()->json(Uas::with(['matakuliah', 'dosen', 'pengumpulan'])->findOrFail($id)); }

    public function update(Request $request, $id)
    {
        $uas = Uas::findOrFail($id);
        
        if ($request->hasFile('soal')) {
            $path = $request->file('soal')->store('uploads/uas', 'public');
            $request->merge(['soal_uas' => $path]);
        }
        
        $uas->update($request->all());
        return response()->json($uas);
    }

    public function destroy($id)
    {
        Uas::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}