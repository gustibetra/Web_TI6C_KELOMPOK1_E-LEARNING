<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{Matakuliah, Krs, Tugas, Absen, Khs};
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        if ($role === 'dosen') {
            $dosen = $user->dosen;
            $jumlahMatkul = $dosen ? $dosen->matakuliah()->count() : 0;
            $jumlahTugas = $dosen ? $dosen->tugas()->count() : 0;
            $jumlahMateri = $dosen ? $dosen->materi()->count() : 0;
            return view('dosen.dashboard', compact('user', 'dosen', 'jumlahMatkul', 'jumlahTugas', 'jumlahMateri'));
        }

        $mahasiswa = $user->mahasiswa;
        $jumlahKrs = $mahasiswa ? $mahasiswa->krs()->count() : 0;
        $jumlahAbsen = $mahasiswa ? $mahasiswa->absen()->count() : 0;
        return view('mahasiswa.dashboard', compact('user', 'mahasiswa', 'jumlahKrs', 'jumlahAbsen'));
    }
}