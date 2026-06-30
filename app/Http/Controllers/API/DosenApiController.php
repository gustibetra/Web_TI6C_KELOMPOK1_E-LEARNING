<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenApiController extends Controller
{
    public function index() { return response()->json(Dosen::with('user')->get()); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|exists:user,id_user',
            'nama_dosen' => 'required|string',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string',
        ]);
        return response()->json(Dosen::create($data), 201);
    }

    public function show($id) { return response()->json(Dosen::with(['user', 'matakuliah'])->findOrFail($id)); }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->update($request->all());
        return response()->json($dosen);
    }

    public function destroy($id)
    {
        Dosen::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}