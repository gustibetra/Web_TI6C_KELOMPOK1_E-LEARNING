<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaApiController extends Controller
{
    public function index() { return response()->json(Mahasiswa::with('user')->get()); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|exists:user,id_user',
            'nama_mahasiswa' => 'required|string',
            'kelas' => 'nullable|string',
            'prodi' => 'nullable|string',
            'semester' => 'nullable|integer',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string',
        ]);
        return response()->json(Mahasiswa::create($data), 201);
    }

    public function show($id) { return response()->json(Mahasiswa::with(['user', 'krs', 'khs'])->findOrFail($id)); }

    public function update(Request $request, $id)
    {
        $mhs = Mahasiswa::findOrFail($id);
        $mhs->update($request->all());
        return response()->json($mhs);
    }

    public function destroy($id)
    {
        Mahasiswa::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}