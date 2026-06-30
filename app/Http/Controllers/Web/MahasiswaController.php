<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{Krs, Materi, Tugas, Uts, Uas, Absen, Khs, Matakuliah, PengumpulanTugas, PengumpulanUts, PengumpulanUas};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    private function getMahasiswa() { return Auth::user()->mahasiswa; }
    private function getIdMatkul() {
        return Krs::where('id_mahasiswa', $this->getMahasiswa()->id_mahasiswa)->pluck('id_matkul');
    }

    // ===== KRS MAHASISWA - Lihat Semua Matkul & Daftar =====
    public function krsIndex()
{
    $mahasiswa = $this->getMahasiswa();
    
    // Ambil KRS yang sudah dipaketkan untuk mahasiswa ini
    $krsData = Krs::with(['matakuliah.dosen'])
                  ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                  ->get();
    
    $totalSks = $krsData->sum(function($krs) {
        return $krs->matakuliah->sks;
    });
    
    return view('mahasiswa.krs.index', compact('mahasiswa', 'krsData', 'totalSks'));
}

    public function krsStore(Request $request)
    {
        $request->validate([
            'id_matkul' => 'required|exists:matakuliah,id_matkul',
            'semester' => 'required|integer|min:1|max:8',
        ]);
        
        // Cek apakah sudah mengambil matkul ini
        $exists = Krs::where('id_mahasiswa', $this->getMahasiswa()->id_mahasiswa)
                     ->where('id_matkul', $request->id_matkul)
                     ->first();
        
        if ($exists) {
            return redirect()->route('mahasiswa.krs.index')->with('error', 'Anda sudah mengambil mata kuliah ini');
        }
        
        Krs::create([
            'id_mahasiswa' => $this->getMahasiswa()->id_mahasiswa,
            'id_matkul' => $request->id_matkul,
            'semester' => $request->semester,
        ]);
        
        return redirect()->route('mahasiswa.krs.index')->with('success', 'Mata kuliah berhasil ditambahkan ke KRS');
    }

    public function krsDestroy($id)
    {
        Krs::where('id_mahasiswa', $this->getMahasiswa()->id_mahasiswa)
           ->findOrFail($id)
           ->delete();
        return redirect()->route('mahasiswa.krs.index')->with('success', 'Mata kuliah dibatalkan');
    }

    // ===== DOWNLOAD KRS PDF =====
    public function downloadKrsPdf()
    {
        $mahasiswa = $this->getMahasiswa();
        
        // Ambil data KRS mahasiswa dengan relasi matakuliah dan dosen
        $krsData = Krs::with(['matakuliah.dosen'])
                      ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                      ->get();
        
        // Hitung total SKS
        $totalSks = $krsData->sum(function($krs) {
            return $krs->matakuliah->sks;
        });
        
        // Data untuk PDF
        $data = [
            'mahasiswa' => $mahasiswa,
            'krsData' => $krsData,
            'totalSks' => $totalSks,
            'tanggal' => now()->format('d F Y'),
        ];
        
        // Generate PDF
        $pdf = Pdf::loadView('mahasiswa.krs.cetak', $data);
        $pdf->setPaper('a4', 'portrait');
        
        // Download dengan nama file
        $filename = 'KRS_' . str_replace(' ', '_', $mahasiswa->nama_mahasiswa) . '_' . $mahasiswa->id_user . '.pdf';
        
        return $pdf->download($filename);
    }

    // ===== MATERI (View + Download) =====
    public function materiIndex()
    {
        $data = Materi::with('matakuliah')->whereIn('id_matkul', $this->getIdMatkul())->get();
        return view('mahasiswa.materi.index', compact('data'));
    }

    // ===== TUGAS (View + Download Soal + Upload Jawaban) =====
    public function tugasIndex()
    {
        $tugas = Tugas::with('matakuliah')->whereIn('id_matkul', $this->getIdMatkul())->get();
        // Cek status pengumpulan
        foreach ($tugas as $t) {
            $t->sudah_kumpul = PengumpulanTugas::where('id_tugas', $t->id_tugas)
                ->where('id_mahasiswa', $this->getMahasiswa()->id_mahasiswa)->first();
        }
        return view('mahasiswa.tugas.index', compact('tugas'));
    }

    public function tugasSubmit(Request $request, $id)
{
    $request->validate([
        'link_google_drive' => 'required|url|max:500',
    ], [
        'link_google_drive.required' => 'Link Google Drive wajib diisi',
        'link_google_drive.url' => 'Format link tidak valid',
    ]);

    // Cek apakah sudah mengumpulkan
    $sudahKumpul = PengumpulanTugas::where('id_tugas', $id)
        ->where('id_mahasiswa', $this->getMahasiswa()->id_mahasiswa)
        ->first();

    if ($sudahKumpul) {
        return redirect()->route('mahasiswa.tugas.index')
            ->with('error', 'Anda sudah mengumpulkan tugas ini');
    }

    // Simpan link Google Drive - JANGAN sertakan id_up_tugas
    PengumpulanTugas::create([
        'id_tugas' => $id,
        'id_mahasiswa' => $this->getMahasiswa()->id_mahasiswa,
        'link_pengumpulan_tugas' => $request->link_google_drive,
        'nilai_tugas' => 0,
        'feedback' => '',
        // created_at dan updated_at akan otomatis terisi
    ]);

    return redirect()->route('mahasiswa.tugas.index')
        ->with('success', 'Tugas berhasil dikumpulkan via Google Drive');
}

    // ===== UTS =====
    public function utsIndex()
{
    $mahasiswa = $this->getMahasiswa();
    $idMatkulList = $this->getIdMatkul();
    
    $uts = Uts::with('matakuliah')
              ->whereIn('id_matkul', $idMatkulList)
              ->get();
    
    // Cek status untuk setiap UTS
    foreach ($uts as $u) {
        // Cek apakah sudah absen untuk matkul ini (menggunakan tabel 'absen')
        $u->sudah_absen = Absen::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                               ->where('id_matkul', $u->id_matkul)
                               ->exists();
        
        // Cek apakah sudah kumpulkan
        $u->sudah_kumpul = PengumpulanUts::where('id_uts', $u->id_uts)
                                         ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                                         ->first();
    }
    
    return view('mahasiswa.uts.index', compact('uts'));
}

// Method baru untuk menyimpan foto absen UTS ke tabel 'absen'
public function utsAbsen(Request $request, $id)
{
    $mahasiswa = $this->getMahasiswa();
    $uts = Uts::findOrFail($id);
    
    $request->validate([
        'foto_bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ], [
        'foto_bukti.required' => 'Foto bukti wajib diupload',
        'foto_bukti.image' => 'File harus berupa gambar',
    ]);
    
    $path = $request->file('foto_bukti')->store('uploads/absen_uts', 'public');
    
    // Simpan ke tabel 'absen' yang sudah ada
    Absen::create([
        'id_mahasiswa' => $mahasiswa->id_mahasiswa,
        'id_matkul' => $uts->id_matkul, // Simpan berdasarkan ID Matkul
        'tanggal_absen' => now(),
        'foto_pash' => $path,
        'status' => 'hadir', // Otomatis dianggap hadir
    ]);
    
    return redirect()->route('mahasiswa.uts.index')
        ->with('success', 'Absensi UTS berhasil! Sekarang Anda bisa download soal dan mengumpulkan jawaban.');
}

public function utsSubmit(Request $request, $id)
{
    $mahasiswa = $this->getMahasiswa();
    $uts = Uts::findOrFail($id);
    
    // Validasi: Harus sudah absen dulu
    $sudahAbsen = Absen::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                       ->where('id_matkul', $uts->id_matkul)
                       ->exists();
    
    if (!$sudahAbsen) {
        return redirect()->route('mahasiswa.uts.index')
            ->with('error', 'Anda HARUS melakukan absensi (upload foto) terlebih dahulu sebelum mengumpulkan UTS!');
    }
    
    $request->validate([
        'link_google_drive' => 'required|url|max:500',
    ], [
        'link_google_drive.required' => 'Link Google Drive wajib diisi',
        'link_google_drive.url' => 'Format link tidak valid',
    ]);
    
    // Cek apakah sudah mengumpulkan
    $sudahKumpul = PengumpulanUts::where('id_uts', $id)
                                 ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                                 ->first();
    
    if ($sudahKumpul) {
        return redirect()->route('mahasiswa.uts.index')
            ->with('error', 'Anda sudah mengumpulkan UTS ini');
    }
    
    PengumpulanUts::create([
        'id_uts' => $id,
        'id_mahasiswa' => $mahasiswa->id_mahasiswa,
        'link_pengumpulan_uts' => $request->link_google_drive,
        'nilai_uts' => 0,
        'feedback' => '',
    ]);
    
    return redirect()->route('mahasiswa.uts.index')
        ->with('success', 'Jawaban UTS berhasil dikumpulkan via Google Drive');
}

    // ===== UAS =====
    public function uasIndex()
{
    $mahasiswa = $this->getMahasiswa();
    $idMatkulList = $this->getIdMatkul();
    
    $uas = Uas::with('matakuliah')
              ->whereIn('id_matkul', $idMatkulList)
              ->get();
    
    // Cek status untuk setiap UAS
    foreach ($uas as $u) {
        // Cek apakah sudah absen UAS ini (gunakan id_matkul, BUKAN id_uas)
        $u->sudah_absen = Absen::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                               ->where('id_matkul', $u->id_matkul)  // ← PERBAIKI: gunakan id_matkul
                               ->first();
        
        // Cek apakah sudah kumpulkan
        $u->sudah_kumpul = PengumpulanUas::where('id_uas', $u->id_uas)
                                         ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                                         ->first();
    }
    
    return view('mahasiswa.uas.index', compact('uas'));
}

public function uasAbsen(Request $request, $id)
{
    $mahasiswa = $this->getMahasiswa();
    $uas = Uas::findOrFail($id);
    
    // Cek apakah sudah absen (gunakan id_matkul, BUKAN id_uas)
    $sudahAbsen = Absen::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                      ->where('id_matkul', $uas->id_matkul)  // ← PERBAIKI: gunakan id_matkul
                      ->first();
    
    if ($sudahAbsen) {
        return redirect()->route('mahasiswa.uas.index')
            ->with('error', 'Anda sudah melakukan absensi UAS ini');
    }
    
    $request->validate([
        'foto_bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ], [
        'foto_bukti.required' => 'Foto bukti wajib diupload',
        'foto_bukti.image' => 'File harus berupa gambar',
    ]);
    
    $path = $request->file('foto_bukti')->store('uploads/absen_uas', 'public');
    
    Absen::create([
        'id_mahasiswa' => $mahasiswa->id_mahasiswa,
        'id_matkul' => $uas->id_matkul,  // ← PERBAIKI: gunakan id_matkul
        'foto_pash' => $path,
        'tanggal_absen' => now(),
        'status' => 'hadir',
    ]);
    
    return redirect()->route('mahasiswa.uas.index')
        ->with('success', 'Absensi UAS berhasil. Sekarang Anda bisa download soal dan mengumpulkan jawaban.');
}

public function uasSubmit(Request $request, $id)
{
    $mahasiswa = $this->getMahasiswa();
    $uas = Uas::findOrFail($id);
    
    // Cek apakah sudah absen (gunakan id_matkul, BUKAN id_uas)
    $sudahAbsen = Absen::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                      ->where('id_matkul', $uas->id_matkul)  // ← PERBAIKI: gunakan id_matkul
                      ->first();
    
    if (!$sudahAbsen) {
        return redirect()->route('mahasiswa.uas.index')
            ->with('error', 'Anda harus melakukan absensi UAS terlebih dahulu');
    }
    
    $request->validate([
        'link_google_drive' => 'required|url|max:500',
        'kritik_dan_saran' => 'nullable|string',
    ], [
        'link_google_drive.required' => 'Link Google Drive wajib diisi',
        'link_google_drive.url' => 'Format link tidak valid',
    ]);
    
    // Cek apakah sudah mengumpulkan
    $sudahKumpul = PengumpulanUas::where('id_uas', $id)
                                 ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                                 ->first();
    
    if ($sudahKumpul) {
        return redirect()->route('mahasiswa.uas.index')
            ->with('error', 'Anda sudah mengumpulkan UAS ini');
    }
    
    PengumpulanUas::create([
        'id_uas' => $id,
        'id_mahasiswa' => $mahasiswa->id_mahasiswa,
        'link_pengumpulan_uas' => $request->link_google_drive,
        'kritik_dan_saran' => $request->kritik_dan_saran ?? '',
        'nilai_uas' => 0,
        'feedback' => '',
    ]);
    
    return redirect()->route('mahasiswa.uas.index')
        ->with('success', 'Jawaban UAS berhasil dikumpulkan via Google Drive');
}

    // ===== ABSEN (View + Upload Foto) =====
    public function absenIndex()
{
    $mahasiswa = $this->getMahasiswa();
    $idMatkulList = $this->getIdMatkul();
    
    $data = Absen::with('matakuliah')
        ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        ->get();
    
    $matakuliahs = Matakuliah::whereIn('id_matkul', $idMatkulList)->get();
    
    // Cek sesi aktif untuk matkul yang diambil mahasiswa
    $sesiAktif = null;
    foreach ($idMatkulList as $idMatkul) {
        $sesi = cache("sesi_absen_{$idMatkul}");
        if ($sesi && $sesi['status'] === 'aktif') {
            $sesiAktif = $sesi;
            $sesiAktif['id_matkul'] = $idMatkul;
            break;
        }
    }
    
    return view('mahasiswa.absen.index', compact('data', 'matakuliahs', 'sesiAktif'));
}

public function absenStore(Request $request)
{
    $mahasiswa = $this->getMahasiswa();
    
    // Cek apakah ada sesi absen yang aktif untuk matkul ini
    $sesiAktif = cache("sesi_absen_{$request->id_matkul}");
    
    if (!$sesiAktif || $sesiAktif['status'] !== 'aktif') {
        return redirect()->route('mahasiswa.absen.index')
            ->with('error', 'Sesi absensi belum dibuka oleh dosen');
    }
    
    // Cek apakah waktu sesi masih valid
    if (time() > $sesiAktif['berakhir_di']) {
        cache()->forget("sesi_absen_{$request->id_matkul}");
        return redirect()->route('mahasiswa.absen.index')
            ->with('error', 'Sesi absensi sudah berakhir');
    }
    
    // Cek apakah mahasiswa sudah absen hari ini untuk matkul ini
    $sudahAbsen = Absen::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        ->where('id_matkul', $request->id_matkul)
        ->whereDate('tanggal_absen', today())
        ->first();
    
    if ($sudahAbsen) {
        return redirect()->route('mahasiswa.absen.index')
            ->with('error', 'Anda sudah melakukan absensi hari ini untuk mata kuliah ini');
    }
    
    $request->validate([
        'id_matkul' => 'required|exists:matakuliah,id_matkul',
        'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);
    
    $path = $request->file('foto')->store('uploads/absen', 'public');
    Absen::create([
        'id_mahasiswa' => $mahasiswa->id_mahasiswa,
        'id_matkul' => $request->id_matkul,
        'tanggal_absen' => now(),
        'foto_pash' => $path,
        'status' => 'pending',
    ]);
    
    return redirect()->route('mahasiswa.absen.index')
        ->with('success', 'Absensi berhasil dikirim');
}

    // ===== KHS (View + Download PDF) =====
public function khsIndex()
{
    $mahasiswa = $this->getMahasiswa();
    
    $data = Khs::with('matakuliah')
        ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        ->get();
    
    // Hitung total SKS dan IPK
    $totalSks = 0;
    $totalNilaiBobot = 0;
    
    foreach ($data as $khs) {
        $sks = $khs->matakuliah->sks;
        $totalSks += $sks;
        
        // Hitung bobot nilai (A=4, B+=3.5, B=3, C+=2.5, C=2, D=1, E=0)
        $grade = strtoupper($khs->grade);
        $bobot = 0;
        switch($grade) {
            case 'A': $bobot = 4.0; break;
            case 'B+': $bobot = 3.5; break;
            case 'B': $bobot = 3.0; break;
            case 'C+': $bobot = 2.5; break;
            case 'C': $bobot = 2.0; break;
            case 'D': $bobot = 1.0; break;
            case 'E': $bobot = 0.0; break;
        }
        
        $totalNilaiBobot += ($bobot * $sks);
    }
    
    $ipk = $totalSks > 0 ? round($totalNilaiBobot / $totalSks, 2) : 0;
    
    return view('mahasiswa.khs.index', compact('data', 'mahasiswa', 'totalSks', 'ipk'));
}

// Download KHS PDF
public function downloadKhsPdf()
{
    $mahasiswa = $this->getMahasiswa();
    
    // Ambil data KHS mahasiswa dengan relasi matakuliah dan dosen
    // HAPUS orderBy karena tidak bisa order by kolom relasi tanpa join
    $khsData = Khs::with(['matakuliah.dosen'])
                  ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                  ->get();
    
    // Urutkan setelah data diambil (di collection level)
    $khsData = $khsData->sortBy(function($khs) {
        return $khs->matakuliah->semester ?? 0;
    })->values();
    
    // Hitung total SKS dan IPK rata-rata
    $totalSks = $khsData->sum(function($khs) {
        return $khs->matakuliah->sks;
    });
    
    $totalBobot = $khsData->sum(function($khs) {
        return $khs->matakuliah->sks * $khs->ipk;
    });
    
    $ipkKumulatif = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;
    
    $data = [
        'mahasiswa' => $mahasiswa,
        'khsData' => $khsData,
        'totalSks' => $totalSks,
        'ipkKumulatif' => $ipkKumulatif,
        'tanggal' => now()->format('d F Y'),
    ];
    
    $pdf = Pdf::loadView('mahasiswa.khs.cetak', $data);
    $pdf->setPaper('a4', 'portrait');
    
    $filename = 'KHS_' . str_replace(' ', '_', $mahasiswa->nama_mahasiswa) . '_' . $mahasiswa->id_user . '.pdf';
    
    return $pdf->download($filename);
}
// ===== PROFILE MAHASISWA =====
public function profile()
{
    $mahasiswa = $this->getMahasiswa();
    return view('mahasiswa.profile.index', compact('mahasiswa'));
}

public function updateProfile(Request $request)
{
    $mahasiswa = $this->getMahasiswa();
    
    $request->validate([
        'nama_mahasiswa' => 'required|string|max:100',
        'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);
    
    $data = ['nama_mahasiswa' => $request->nama_mahasiswa];
    
    if ($request->hasFile('foto_profil')) {
        // Hapus foto lama jika ada
        if ($mahasiswa->foto_profil) {
            Storage::disk('public')->delete($mahasiswa->foto_profil);
        }
        
        $data['foto_profil'] = $request->file('foto_profil')->store('uploads/profile', 'public');
    }
    
    $mahasiswa->update($data);
    
    return redirect()->route('profile')->with('success', 'Profil berhasil diupdate');
}

public function updatePassword(Request $request)
{
    $mahasiswa = $this->getMahasiswa();
    
    $request->validate([
        'password_lama' => 'required',
        'password_baru' => 'required|min:8|confirmed',
    ]);
    
    // Cek password lama
    if (!Hash::check($request->password_lama, $mahasiswa->user->password)) {
        return redirect()->back()->with('error', 'Password lama salah');
    }
    
    // Update password
    $mahasiswa->user->update([
        'password' => Hash::make($request->password_baru),
    ]);
    
    return redirect()->route('profile')->with('success', 'Password berhasil diubah');
}
}