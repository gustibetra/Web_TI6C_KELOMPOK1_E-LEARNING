<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;

class MateriApiController extends Controller
{
    public function index() { return response()->json(Materi::with(['matakuliah', 'dosen'])->get()); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'file_path' => 'nullable|string',
            'deksripsi' => 'nullable|string',
        ]);
        
        // Handle file upload jika ada
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads/materi', 'public');
            $data['file_path'] = $path;
        }
        
        return response()->json(Materi::create($data), 201);
    }

    public function show($id) { return response()->json(Materi::with(['matakuliah', 'dosen'])->findOrFail($id)); }

    public function update(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);
        
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads/materi', 'public');
            $request->merge(['file_path' => $path]);
        }
        
        $materi->update($request->all());
        return response()->json($materi);
    }

    public function destroy($id)
    {
        Materi::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}