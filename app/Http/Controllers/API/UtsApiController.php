<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Uts;
use Illuminate\Http\Request;

class UtsApiController extends Controller
{
    public function index() { return response()->json(Uts::with(['matakuliah', 'dosen'])->get()); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'deadline' => 'required|string',
        ]);
        
        if ($request->hasFile('soal')) {
            $path = $request->file('soal')->store('uploads/uts', 'public');
            $data['soal_uts'] = $path;
        }
        
        return response()->json(Uts::create($data), 201);
    }

    public function show($id) { return response()->json(Uts::with(['matakuliah', 'dosen', 'pengumpulan'])->findOrFail($id)); }

    public function update(Request $request, $id)
    {
        $uts = Uts::findOrFail($id);
        
        if ($request->hasFile('soal')) {
            $path = $request->file('soal')->store('uploads/uts', 'public');
            $request->merge(['soal_uts' => $path]);
        }
        
        $uts->update($request->all());
        return response()->json($uts);
    }

    public function destroy($id)
    {
        Uts::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}