<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{Matakuliah, Materi, Tugas, Uts, Uas, Absen, Khs, Krs, PengumpulanTugas, PengumpulanUts, PengumpulanUas, Mahasiswa};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    private function getDosen() { return Auth::user()->dosen; }
    private function getIdMatkul() { 
        return Matakuliah::where('id_dosen', $this->getDosen()->id_dosen)->pluck('id_matkul'); 
    }

    // ===== MATAKULIAH CRUD =====
    public function matakuliahIndex()
    {
        $data = Matakuliah::where('id_dosen', $this->getDosen()->id_dosen)->get();
        return view('dosen.matakuliah.index', compact('data'));
    }

    public function matakuliahStore(Request $request)
    {
        $request->validate([
            'kode_matkul' => 'required|string|max:20',
            'nama_matkul' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'waktu_matkul' => 'required|string',
        ]);
        Matakuliah::create([
            'id_dosen' => $this->getDosen()->id_dosen,
            'kode_matkul' => $request->kode_matkul,
            'nama_matkul' => $request->nama_matkul,
            'sks' => $request->sks,
            'semester' => $request->semester,
            'waktu_matkul' => $request->waktu_matkul,
        ]);
        return redirect()->route('dosen.matakuliah.index')->with('success', 'Matakuliah berhasil ditambahkan');
    }

    public function matakuliahUpdate(Request $request, $id)
    {
        $request->validate([
            'kode_matkul' => 'required|string',
            'nama_matkul' => 'required|string',
            'sks' => 'required|integer',
            'semester' => 'required|integer',
            'waktu_matkul' => 'required|string',
        ]);
        $mk = Matakuliah::where('id_dosen', $this->getDosen()->id_dosen)->findOrFail($id);
        $mk->update($request->only(['kode_matkul','nama_matkul','sks','semester','waktu_matkul']));
        return redirect()->route('dosen.matakuliah.index')->with('success', 'Matakuliah berhasil diupdate');
    }

    public function matakuliahDestroy($id)
    {
        Matakuliah::where('id_dosen', $this->getDosen()->id_dosen)->findOrFail($id)->delete();
        return redirect()->route('dosen.matakuliah.index')->with('success', 'Matakuliah dihapus');
    }

    // ===== MATERI CRUD =====
    public function materiIndex()
    {
        $data = Materi::with('matakuliah')->where('id_dosen', $this->getDosen()->id_dosen)->get();
        $matakuliahs = Matakuliah::where('id_dosen', $this->getDosen()->id_dosen)->get();
        return view('dosen.materi.index', compact('data', 'matakuliahs'));
    }

    public function materiStore(Request $request)
    {
        $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'deksripsi' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);
        $path = $request->file('file')->store('uploads/materi', 'public');
        Materi::create([
            'id_matkul' => $request->id_matkul,
            'id_dosen' => $this->getDosen()->id_dosen,
            'deksripsi' => $request->deksripsi,
            'file_path' => $path,
        ]);
        return redirect()->route('dosen.materi.index')->with('success', 'Materi ditambahkan');
    }

    public function materiUpdate(Request $request, $id)
    {
        $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'deksripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);
        $materi = Materi::where('id_dosen', $this->getDosen()->id_dosen)->findOrFail($id);
        $data = ['id_matkul' => $request->id_matkul, 'deksripsi' => $request->deksripsi];
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('uploads/materi', 'public');
        }
        $materi->update($data);
        return redirect()->route('dosen.materi.index')->with('success', 'Materi diupdate');
    }

    public function materiDestroy($id)
    {
        Materi::where('id_dosen', $this->getDosen()->id_dosen)->findOrFail($id)->delete();
        return redirect()->route('dosen.materi.index')->with('success', 'Materi dihapus');
    }

    // ===== TUGAS CRUD =====
    public function tugasIndex()
    {
        $data = Tugas::with('matakuliah')->where('id_dosen', $this->getDosen()->id_dosen)->get();
        $matakuliahs = Matakuliah::where('id_dosen', $this->getDosen()->id_dosen)->get();
        return view('dosen.tugas.index', compact('data', 'matakuliahs'));
    }

    public function tugasStore(Request $request)
    {
        $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'judul' => 'required|string',
            'deskripsi' => 'required|string',
            'deadline' => 'required|string',
            'soal' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);
        Tugas::create([
            'id_matkul' => $request->id_matkul,
            'id_dosen' => $this->getDosen()->id_dosen,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline,
            'soal_tugas' => $request->file('soal')->store('uploads/tugas', 'public'),
        ]);
        return redirect()->route('dosen.tugas.index')->with('success', 'Tugas ditambahkan');
    }

    public function tugasUpdate(Request $request, $id)
    {
        $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'judul' => 'required|string',
            'deskripsi' => 'required|string',
            'deadline' => 'required|string',
            'soal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);
        $tugas = Tugas::where('id_dosen', $this->getDosen()->id_dosen)->findOrFail($id);
        $data = $request->only(['id_matkul','judul','deskripsi','deadline']);
        if ($request->hasFile('soal')) {
            $data['soal_tugas'] = $request->file('soal')->store('uploads/tugas', 'public');
        }
        $tugas->update($data);
        return redirect()->route('dosen.tugas.index')->with('success', 'Tugas diupdate');
    }

    public function tugasDestroy($id)
    {
        Tugas::where('id_dosen', $this->getDosen()->id_dosen)->findOrFail($id)->delete();
        return redirect()->route('dosen.tugas.index')->with('success', 'Tugas dihapus');
    }

    // ===== UTS CRUD =====
    public function utsIndex()
    {
        $dosen = $this->getDosen();
        $data = Uts::with('matakuliah')->where('id_dosen', $dosen->id_dosen)->get();
        $matakuliahs = Matakuliah::where('id_dosen', $dosen->id_dosen)->get();
        return view('dosen.uts.index', compact('data', 'matakuliahs', 'dosen'));
    }

    public function utsStore(Request $request)
    {
        $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'deadline' => 'required|string',
            'soal' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'buka_absen' => 'nullable|boolean',
            'durasi_absen' => 'nullable|integer|min:5|max:180',
        ]);
        
        $idMatkul = $request->id_matkul;
        $dosen = $this->getDosen();
        
        $path = $request->file('soal')->store('uploads/uts', 'public');
        
        Uts::create([
            'id_matkul' => $idMatkul,
            'id_dosen' => $dosen->id_dosen,
            'deadline' => $request->deadline,
            'soal_uts' => $path,
        ]);
        
        if ($request->buka_absen && $request->durasi_absen) {
            $durasi = (int) $request->durasi_absen;
            $waktuBuka = now();
            $waktuTutup = now()->addMinutes($durasi);
            
            $sesiData = [
                'id_matkul' => $idMatkul,
                'id_dosen' => $dosen->id_dosen,
                'status' => 'aktif',
                'waktu_buka' => $waktuBuka->format('H:i:s'),
                'waktu_tutup' => $waktuTutup->format('H:i:s'),
                'durasi_menit' => $durasi,
                'tanggal' => $waktuBuka->format('Y-m-d'),
                'berakhir_di' => $waktuTutup->timestamp,
                'jenis' => 'uts',
            ];
            
            cache()->put("sesi_absen_uts_{$idMatkul}", $sesiData, $durasi * 60);
            
            $idMatkulList = $this->getIdMatkul();
            foreach ($idMatkulList as $idMk) {
                if ($idMk != $idMatkul) {
                    cache()->forget("sesi_absen_{$idMk}");
                }
            }
        }
        
        return redirect()->route('dosen.uts.index')->with('success', 'UTS berhasil ditambahkan' . ($request->buka_absen ? ' dan sesi absensi dibuka' : ''));
    }

    public function utsUpdate(Request $request, $id)
    {
        $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'deadline' => 'required|string',
            'soal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'buka_absen' => 'nullable|boolean',
            'durasi_absen' => 'nullable|integer|min:5|max:180',
        ]);
        
        $uts = Uts::where('id_dosen', $this->getDosen()->id_dosen)->findOrFail($id);
        $data = $request->only(['id_matkul','deadline']);
        
        if ($request->hasFile('soal')) {
            $data['soal_uts'] = $request->file('soal')->store('uploads/uts', 'public');
        }
        
        $uts->update($data);
        
        if ($request->buka_absen && $request->durasi_absen) {
            $durasi = (int) $request->durasi_absen;
            $waktuBuka = now();
            $waktuTutup = now()->addMinutes($durasi);
            
            $sesiData = [
                'id_matkul' => $request->id_matkul,
                'id_dosen' => $this->getDosen()->id_dosen,
                'status' => 'aktif',
                'waktu_buka' => $waktuBuka->format('H:i:s'),
                'waktu_tutup' => $waktuTutup->format('H:i:s'),
                'durasi_menit' => $durasi,
                'tanggal' => $waktuBuka->format('Y-m-d'),
                'berakhir_di' => $waktuTutup->timestamp,
                'jenis' => 'uts',
            ];
            
            cache()->put("sesi_absen_uts_{$request->id_matkul}", $sesiData, $durasi * 60);
        }
        
        return redirect()->route('dosen.uts.index')->with('success', 'UTS berhasil diupdate');
    }

    public function utsDestroy($id)
    {
        Uts::where('id_dosen', $this->getDosen()->id_dosen)->findOrFail($id)->delete();
        return redirect()->route('dosen.uts.index')->with('success', 'UTS dihapus');
    }

    public function tutupSesiAbsenUts(Request $request)
    {
        $request->validate([
            'id_matkul' => 'required',
        ]);
        
        $idMatkul = $request->id_matkul;
        cache()->forget("sesi_absen_uts_{$idMatkul}");
        
        return redirect()->route('dosen.uts.index')->with('success', 'Sesi absensi UTS berhasil ditutup');
    }

    public function utsAbsensiIndex($id)
    {
        $dosen = $this->getDosen();
        $uts = Uts::with('matakuliah')->where('id_dosen', $dosen->id_dosen)->findOrFail($id);
        
        $mahasiswaList = Krs::with('mahasiswa')
            ->where('id_matkul', $uts->id_matkul)
            ->get();
        
        $sudahAbsenIds = Absen::whereIn('id_mahasiswa', $mahasiswaList->pluck('id_mahasiswa'))
            ->where('id_matkul', $uts->id_matkul)
            ->pluck('id_mahasiswa')
            ->toArray();
        
        $dataAbsen = [];
        $belumAbsen = [];
        
        foreach ($mahasiswaList as $krs) {
            $mhs = $krs->mahasiswa;
            if (in_array($mhs->id_mahasiswa, $sudahAbsenIds)) {
                $absen = Absen::where('id_mahasiswa', $mhs->id_mahasiswa)
                             ->where('id_matkul', $uts->id_matkul)
                             ->first();
                $dataAbsen[] = [
                    'mahasiswa' => $mhs,
                    'absen' => $absen,
                ];
            } else {
                $belumAbsen[] = $mhs;
            }
        }
        
        return view('dosen.uts.absensi', compact('uts', 'dataAbsen', 'belumAbsen'));
    }

    public function utsAbsensiManual(Request $request, $utsId)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
        ]);
        
        $dosen = $this->getDosen();
        $uts = Uts::where('id_dosen', $dosen->id_dosen)->findOrFail($utsId);
        
        $sudahAbsen = Absen::where('id_mahasiswa', $request->id_mahasiswa)
                          ->where('id_matkul', $uts->id_matkul)
                          ->first();
        
        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Mahasiswa sudah absen sebelumnya');
        }
        
        Absen::create([
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_matkul' => $uts->id_matkul,
            'tanggal_absen' => now(),
            'foto_pash' => 'manual_absen.jpg',
            'status' => 'hadir',
        ]);
        
        return redirect()->back()->with('success', 'Mahasiswa berhasil diabsen manual');
    }

    // ===== UAS CRUD =====
    public function uasIndex()
    {
        $dosen = $this->getDosen();
        $data = Uas::with('matakuliah')->where('id_dosen', $dosen->id_dosen)->get();
        $matakuliahs = Matakuliah::where('id_dosen', $dosen->id_dosen)->get();
        return view('dosen.uas.index', compact('data', 'matakuliahs', 'dosen'));
    }

    public function uasStore(Request $request)
    {
        $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'deadline' => 'required|string',
            'soal' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'buka_absen' => 'nullable|boolean',
            'durasi_absen' => 'nullable|integer|min:5|max:180',
        ]);
        
        $idMatkul = $request->id_matkul;
        $dosen = $this->getDosen();
        
        $path = $request->file('soal')->store('uploads/uas', 'public');
        
        Uas::create([
            'id_matkul' => $idMatkul,
            'id_dosen' => $dosen->id_dosen,
            'deadline' => $request->deadline,
            'soal_uas' => $path,
        ]);
        
        if ($request->buka_absen && $request->durasi_absen) {
            $durasi = (int) $request->durasi_absen;
            $waktuBuka = now();
            $waktuTutup = now()->addMinutes($durasi);
            
            $sesiData = [
                'id_matkul' => $idMatkul,
                'id_dosen' => $dosen->id_dosen,
                'status' => 'aktif',
                'waktu_buka' => $waktuBuka->format('H:i:s'),
                'waktu_tutup' => $waktuTutup->format('H:i:s'),
                'durasi_menit' => $durasi,
                'tanggal' => $waktuBuka->format('Y-m-d'),
                'berakhir_di' => $waktuTutup->timestamp,
                'jenis' => 'uas',
            ];
            
            cache()->put("sesi_absen_uas_{$idMatkul}", $sesiData, $durasi * 60);
            
            $idMatkulList = $this->getIdMatkul();
            foreach ($idMatkulList as $idMk) {
                if ($idMk != $idMatkul) {
                    cache()->forget("sesi_absen_{$idMk}");
                    cache()->forget("sesi_absen_uts_{$idMk}");
                }
            }
        }
        
        return redirect()->route('dosen.uas.index')->with('success', 'UAS berhasil ditambahkan' . ($request->buka_absen ? ' dan sesi absensi dibuka' : ''));
    }

    public function uasUpdate(Request $request, $id)
    {
        $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'deadline' => 'required|string',
            'soal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'buka_absen' => 'nullable|boolean',
            'durasi_absen' => 'nullable|integer|min:5|max:180',
        ]);
        
        $uas = Uas::where('id_dosen', $this->getDosen()->id_dosen)->findOrFail($id);
        $data = $request->only(['id_matkul','deadline']);
        
        if ($request->hasFile('soal')) {
            $data['soal_uas'] = $request->file('soal')->store('uploads/uas', 'public');
        }
        
        $uas->update($data);
        
        if ($request->buka_absen && $request->durasi_absen) {
            $durasi = (int) $request->durasi_absen;
            $waktuBuka = now();
            $waktuTutup = now()->addMinutes($durasi);
            
            $sesiData = [
                'id_matkul' => $request->id_matkul,
                'id_dosen' => $this->getDosen()->id_dosen,
                'status' => 'aktif',
                'waktu_buka' => $waktuBuka->format('H:i:s'),
                'waktu_tutup' => $waktuTutup->format('H:i:s'),
                'durasi_menit' => $durasi,
                'tanggal' => $waktuBuka->format('Y-m-d'),
                'berakhir_di' => $waktuTutup->timestamp,
                'jenis' => 'uas',
            ];
            
            cache()->put("sesi_absen_uas_{$request->id_matkul}", $sesiData, $durasi * 60);
        }
        
        return redirect()->route('dosen.uas.index')->with('success', 'UAS berhasil diupdate');
    }

    public function uasDestroy($id)
    {
        Uas::where('id_dosen', $this->getDosen()->id_dosen)->findOrFail($id)->delete();
        return redirect()->route('dosen.uas.index')->with('success', 'UAS dihapus');
    }

    public function tutupSesiAbsenUas(Request $request)
    {
        $request->validate([
            'id_matkul' => 'required',
        ]);
        
        $idMatkul = $request->id_matkul;
        cache()->forget("sesi_absen_uas_{$idMatkul}");
        
        return redirect()->route('dosen.uas.index')->with('success', 'Sesi absensi UAS berhasil ditutup');
    }

    public function uasAbsensiIndex($id)
    {
        $dosen = $this->getDosen();
        $uas = Uas::with('matakuliah')->where('id_dosen', $dosen->id_dosen)->findOrFail($id);
        
        $mahasiswaList = Krs::with('mahasiswa')
            ->where('id_matkul', $uas->id_matkul)
            ->get();
        
        $sudahAbsenIds = Absen::whereIn('id_mahasiswa', $mahasiswaList->pluck('id_mahasiswa'))
            ->where('id_matkul', $uas->id_matkul)
            ->pluck('id_mahasiswa')
            ->toArray();
        
        $dataAbsen = [];
        $belumAbsen = [];
        
        foreach ($mahasiswaList as $krs) {
            $mhs = $krs->mahasiswa;
            $absen = Absen::where('id_mahasiswa', $mhs->id_mahasiswa)
                         ->where('id_matkul', $uas->id_matkul)
                         ->first();
            
            if ($absen) {
                $dataAbsen[] = [
                    'mahasiswa' => $mhs,
                    'absen' => $absen,
                ];
            } else {
                $belumAbsen[] = $mhs;
            }
        }
        
        return view('dosen.uas.absensi', compact('uas', 'dataAbsen', 'belumAbsen'));
    }

    public function uasAbsensiManual(Request $request, $uasId)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
        ]);
        
        $dosen = $this->getDosen();
        $uas = Uas::where('id_dosen', $dosen->id_dosen)->findOrFail($uasId);
        
        $sudahAbsen = Absen::where('id_mahasiswa', $request->id_mahasiswa)
                          ->where('id_matkul', $uas->id_matkul)
                          ->first();
        
        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Mahasiswa sudah absen sebelumnya');
        }
        
        Absen::create([
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_matkul' => $uas->id_matkul,
            'tanggal_absen' => now(),
            'foto_pash' => 'manual_absen.jpg',
            'status' => 'hadir',
        ]);
        
        return redirect()->back()->with('success', 'Mahasiswa berhasil diabsen manual');
    }

    // ===== ABSEN =====
    public function absenIndex()
    {
        $dosen = $this->getDosen();
        $idMatkulList = $this->getIdMatkul();
        
        $data = Absen::with(['mahasiswa', 'matakuliah'])
            ->whereIn('id_matkul', $idMatkulList)
            ->orderBy('tanggal_absen', 'desc')
            ->get();
        
        $sesiAktif = null;
        foreach ($idMatkulList as $idMatkul) {
            $sesi = cache("sesi_absen_{$idMatkul}");
            if ($sesi && $sesi['status'] === 'aktif') {
                $sesiAktif = $sesi;
                $sesiAktif['id_matkul'] = $idMatkul;
                break;
            }
        }
        
        $riwayatSesi = cache("riwayat_sesi_{$dosen->id_dosen}", []);
        
        return view('dosen.absen.index', compact('dosen', 'data', 'sesiAktif', 'riwayatSesi'));
    }

    public function bukaSesiAbsen(Request $request)
    {
        $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'durasi_menit' => 'required|integer|min:5|max:180',
        ]);

        $idMatkul = $request->id_matkul;
        $durasi = (int) $request->durasi_menit;
        $dosen = $this->getDosen();
        
        $idMatkulList = $this->getIdMatkul();
        foreach ($idMatkulList as $idMk) {
            cache()->forget("sesi_absen_{$idMk}");
        }
        
        $waktuBuka = now();
        $waktuTutup = now()->addMinutes($durasi);
        
        $sesiData = [
            'id_matkul' => $idMatkul,
            'id_dosen' => $dosen->id_dosen,
            'status' => 'aktif',
            'waktu_buka' => $waktuBuka->format('H:i:s'),
            'waktu_tutup' => $waktuTutup->format('H:i:s'),
            'durasi_menit' => $durasi,
            'tanggal' => $waktuBuka->format('Y-m-d'),
            'berakhir_di' => $waktuTutup->timestamp,
        ];
        
        cache()->put("sesi_absen_{$idMatkul}", $sesiData, $durasi * 60);
        
        $riwayat = cache("riwayat_sesi_{$dosen->id_dosen}", []);
        array_unshift($riwayat, $sesiData);
        $riwayat = array_slice($riwayat, 0, 20);
        cache()->put("riwayat_sesi_{$dosen->id_dosen}", $riwayat);
        
        return redirect()->route('dosen.absen.index')->with('success', "Sesi absensi berhasil dibuka untuk {$durasi} menit");
    }

    public function tutupSesiAbsen(Request $request)
    {
        $request->validate([
            'id_matkul' => 'required',
        ]);
        
        $idMatkul = $request->id_matkul;
        $dosen = $this->getDosen();
        
        cache()->forget("sesi_absen_{$idMatkul}");
        
        $riwayat = cache("riwayat_sesi_{$dosen->id_dosen}", []);
        foreach ($riwayat as &$r) {
            if ($r['id_matkul'] == $idMatkul && $r['status'] === 'aktif') {
                $r['status'] = 'nonaktif';
                break;
            }
        }
        cache()->put("riwayat_sesi_{$dosen->id_dosen}", $riwayat);
        
        return redirect()->route('dosen.absen.index')->with('success', 'Sesi absensi berhasil ditutup');
    }

    public function absenUpdate(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:hadir,tidak_hadir,pending']);
        $absen = Absen::whereIn('id_matkul', $this->getIdMatkul())->findOrFail($id);
        $absen->update(['status' => $request->status]);
        return redirect()->route('dosen.absen.index')->with('success', 'Status absen diupdate');
    }

    // ===== KHS CRUD =====
    public function khsIndex()
    {
        $data = Khs::with(['mahasiswa', 'matakuliah'])
            ->whereIn('id_matkul', $this->getIdMatkul())->get();
        $mahasiswas = Mahasiswa::all();
        $matakuliahs = Matakuliah::where('id_dosen', $this->getDosen()->id_dosen)->get();
        return view('dosen.khs.index', compact('data', 'mahasiswas', 'matakuliahs'));
    }

    public function khsStore(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'nilai_akhir' => 'required|numeric|min:0|max:100',
            'grade' => 'required|string',
            'ipk' => 'required|numeric|min:0|max:4',
        ]);
        Khs::create($request->only(['id_mahasiswa','id_matkul','nilai_akhir','grade','ipk']));
        return redirect()->route('dosen.khs.index')->with('success', 'Nilai ditambahkan');
    }

    public function khsUpdate(Request $request, $id)
    {
        $request->validate([
            'nilai_akhir' => 'required|numeric|min:0|max:100',
            'grade' => 'required|string',
            'ipk' => 'required|numeric|min:0|max:4',
        ]);
        $khs = Khs::whereIn('id_matkul', $this->getIdMatkul())->findOrFail($id);
        $khs->update($request->only(['nilai_akhir','grade','ipk']));
        return redirect()->route('dosen.khs.index')->with('success', 'Nilai diupdate');
    }

    public function khsDestroy($id)
    {
        Khs::whereIn('id_matkul', $this->getIdMatkul())->findOrFail($id)->delete();
        return redirect()->route('dosen.khs.index')->with('success', 'Nilai dihapus');
    }

    // ===== KRS DOSEN =====
    public function krsIndex()
    {
        $data = Matakuliah::where('id_dosen', $this->getDosen()->id_dosen)->get();
        return view('dosen.krs.index', compact('data'));
    }

    public function krsStore(Request $request)
    {
        $request->validate([
            'kode_matkul' => 'required|string|max:20',
            'nama_matkul' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'waktu_matkul' => 'required|string',
        ]);

        Matakuliah::create([
            'id_dosen' => $this->getDosen()->id_dosen,
            'kode_matkul' => $request->kode_matkul,
            'nama_matkul' => $request->nama_matkul,
            'sks' => $request->sks,
            'semester' => $request->semester,
            'waktu_matkul' => $request->waktu_matkul,
        ]);

        return redirect()->route('dosen.krs.index')->with('success', 'Mata kuliah berhasil ditambahkan ke daftar KRS');
    }

    public function krsUpdate(Request $request, $id)
    {
        $request->validate([
            'kode_matkul' => 'required|string|max:20',
            'nama_matkul' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'waktu_matkul' => 'required|string',
        ]);

        $mk = Matakuliah::where('id_dosen', $this->getDosen()->id_dosen)->findOrFail($id);
        $mk->update($request->only(['kode_matkul', 'nama_matkul', 'sks', 'semester', 'waktu_matkul']));

        return redirect()->route('dosen.krs.index')->with('success', 'Mata kuliah berhasil diupdate');
    }

    public function krsDestroy($id)
    {
        Matakuliah::where('id_dosen', $this->getDosen()->id_dosen)->findOrFail($id)->delete();
        return redirect()->route('dosen.krs.index')->with('success', 'Mata kuliah berhasil dihapus dari daftar KRS');
    }

    // ===== PENGUMPULAN TUGAS =====
    public function pengumpulanTugasIndex()
    {
        $idTugas = Tugas::whereIn('id_matkul', $this->getIdMatkul())->pluck('id_tugas');
        $data = PengumpulanTugas::with(['tugas.matakuliah', 'mahasiswa'])
            ->whereIn('id_tugas', $idTugas)
            ->get();
        return view('dosen.pengumpulan.tugas', compact('data'));
    }

    public function pengumpulanTugasUpdate(Request $request, $id)
    {
        $request->validate([
            'nilai_tugas' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);
        $p = PengumpulanTugas::findOrFail($id);
        $p->update($request->only(['nilai_tugas','feedback']));
        return redirect()->route('dosen.pengumpulan.tugas')->with('success', 'Nilai tugas diupdate');
    }

    // ===== PENGUMPULAN UTS =====
    public function pengumpulanUtsIndex()
    {
        $idUts = Uts::whereIn('id_matkul', $this->getIdMatkul())->pluck('id_uts');
        $data = PengumpulanUts::with(['uts.matakuliah', 'mahasiswa'])
            ->whereIn('id_uts', $idUts)->get();
        return view('dosen.pengumpulan.uts', compact('data'));
    }

    public function pengumpulanUtsUpdate(Request $request, $id)
    {
        $request->validate([
            'nilai_uts' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);
        $p = PengumpulanUts::findOrFail($id);
        $p->update($request->only(['nilai_uts','feedback']));
        return redirect()->route('dosen.pengumpulan.uts')->with('success', 'Nilai UTS diupdate');
    }

    // ===== PENGUMPULAN UAS =====
    public function pengumpulanUasIndex()
    {
        $idUas = Uas::whereIn('id_matkul', $this->getIdMatkul())->pluck('id_uas');
        $data = PengumpulanUas::with(['uas.matakuliah', 'mahasiswa'])
            ->whereIn('id_uas', $idUas)->get();
        return view('dosen.pengumpulan.uas', compact('data'));
    }

    public function pengumpulanUasUpdate(Request $request, $id)
    {
        $request->validate([
            'nilai_uas' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);
        $p = PengumpulanUas::findOrFail($id);
        $p->update($request->only(['nilai_uas','feedback']));
        return redirect()->route('dosen.pengumpulan.uas')->with('success', 'Nilai UAS diupdate');
    }

    // ===== PROFILE DOSEN =====
public function profile()
{
    $dosen = $this->getDosen();
    return view('dosen.profile.index', compact('dosen'));
}

public function updateProfile(Request $request)
{
    $dosen = $this->getDosen();
    
    $request->validate([
        'nama_dosen' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'no_hp' => 'required|string|max:20',
        'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);
    
    $data = [
        'nama_dosen' => $request->nama_dosen,
        'email' => $request->email,
        'no_hp' => $request->no_hp,
    ];
    
    if ($request->hasFile('foto_profil')) {
        // Hapus foto lama jika ada
        if ($dosen->foto_profil) {
            Storage::disk('public')->delete($dosen->foto_profil);
        }
        
        $data['foto_profil'] = $request->file('foto_profil')->store('uploads/profile', 'public');
    }
    
    $dosen->update($data);
    
    return redirect()->route('dosen.profile')->with('success', 'Profil berhasil diupdate');
}

public function updatePassword(Request $request)
{
    $dosen = $this->getDosen();
    
    $request->validate([
        'password_lama' => 'required',
        'password_baru' => 'required|min:8|confirmed',
    ]);
    
    // Cek password lama
    if (!Hash::check($request->password_lama, $dosen->user->password)) {
        return redirect()->back()->with('error', 'Password lama salah');
    }
    
    // Update password
    $dosen->user->update([
        'password' => Hash::make($request->password_baru),
    ]);
    
    return redirect()->route('dosen.profile')->with('success', 'Password berhasil diubah');
}
}