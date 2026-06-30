<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DosenController;
use App\Http\Controllers\Web\MahasiswaController;

Route::get('/', fn() => redirect('/login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== ROUTES PROFILE MAHASISWA (DI LUAR GROUP) =====
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/profile', [MahasiswaController::class, 'profile'])->name('profile');
    Route::put('/profile', [MahasiswaController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [MahasiswaController::class, 'updatePassword'])->name('profile.password');
});

// ===== ROUTES PROFILE DOSEN (DI LUAR GROUP) =====
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/profile-dosen', [DosenController::class, 'profile'])->name('dosen.profile');
    Route::put('/profile-dosen', [DosenController::class, 'updateProfile'])->name('dosen.profile.update');
    Route::put('/profile-dosen/password', [DosenController::class, 'updatePassword'])->name('dosen.profile.password');
});

// ===== ROUTES DOSEN (FULL CRUD) =====
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Matakuliah CRUD
    Route::get('/matakuliah', [DosenController::class, 'matakuliahIndex'])->name('matakuliah.index');
    Route::post('/matakuliah', [DosenController::class, 'matakuliahStore'])->name('matakuliah.store');
    Route::put('/matakuliah/{id}', [DosenController::class, 'matakuliahUpdate'])->name('matakuliah.update');
    Route::delete('/matakuliah/{id}', [DosenController::class, 'matakuliahDestroy'])->name('matakuliah.destroy');
    
    // Materi CRUD
    Route::get('/materi', [DosenController::class, 'materiIndex'])->name('materi.index');
    Route::post('/materi', [DosenController::class, 'materiStore'])->name('materi.store');
    Route::put('/materi/{id}', [DosenController::class, 'materiUpdate'])->name('materi.update');
    Route::delete('/materi/{id}', [DosenController::class, 'materiDestroy'])->name('materi.destroy');
    
    // Tugas CRUD
    Route::get('/tugas', [DosenController::class, 'tugasIndex'])->name('tugas.index');
    Route::post('/tugas', [DosenController::class, 'tugasStore'])->name('tugas.store');
    Route::put('/tugas/{id}', [DosenController::class, 'tugasUpdate'])->name('tugas.update');
    Route::delete('/tugas/{id}', [DosenController::class, 'tugasDestroy'])->name('tugas.destroy');
    
    // UTS CRUD
    Route::get('/uts', [DosenController::class, 'utsIndex'])->name('uts.index');
    Route::post('/uts', [DosenController::class, 'utsStore'])->name('uts.store');
    Route::put('/uts/{id}', [DosenController::class, 'utsUpdate'])->name('uts.update');
    Route::delete('/uts/{id}', [DosenController::class, 'utsDestroy'])->name('uts.destroy');
    Route::post('/uts/tutup-sesi-absen', [DosenController::class, 'tutupSesiAbsenUts'])->name('uts.tutup-sesi-absen');
    Route::get('/uts/{id}/absensi', [DosenController::class, 'utsAbsensiIndex'])->name('uts.absensi');
    Route::post('/uts/{id}/absensi/manual', [DosenController::class, 'utsAbsensiManual'])->name('uts.absensi.manual');
    
    // UAS CRUD
    Route::get('/uas', [DosenController::class, 'uasIndex'])->name('uas.index');
    Route::post('/uas', [DosenController::class, 'uasStore'])->name('uas.store');
    Route::put('/uas/{id}', [DosenController::class, 'uasUpdate'])->name('uas.update');
    Route::delete('/uas/{id}', [DosenController::class, 'uasDestroy'])->name('uas.destroy');
    Route::post('/uas/tutup-sesi-absen', [DosenController::class, 'tutupSesiAbsenUas'])->name('uas.tutup-sesi-absen');
    Route::get('/uas/{id}/absensi', [DosenController::class, 'uasAbsensiIndex'])->name('uas.absensi');
    Route::post('/uas/{id}/absensi/manual', [DosenController::class, 'uasAbsensiManual'])->name('uas.absensi.manual');
    
    // Routes Dosen - Absensi dengan Sesi
    Route::get('/absen', [DosenController::class, 'absenIndex'])->name('absen.index');
    Route::post('/absen/buka-sesi', [DosenController::class, 'bukaSesiAbsen'])->name('absen.buka-sesi');
    Route::post('/absen/tutup-sesi', [DosenController::class, 'tutupSesiAbsen'])->name('absen.tutup-sesi');
    Route::put('/absen/{id}', [DosenController::class, 'absenUpdate'])->name('absen.update');
    
    // KHS (CRUD nilai)
    Route::get('/khs', [DosenController::class, 'khsIndex'])->name('khs.index');
    Route::post('/khs', [DosenController::class, 'khsStore'])->name('khs.store');
    Route::put('/khs/{id}', [DosenController::class, 'khsUpdate'])->name('khs.update');
    Route::delete('/khs/{id}', [DosenController::class, 'khsDestroy'])->name('khs.destroy');
    
    // KRS (view mahasiswa yang ambil matkul)
    Route::get('/krs', [DosenController::class, 'krsIndex'])->name('krs.index');
    Route::post('/krs', [DosenController::class, 'krsStore'])->name('krs.store');
    Route::put('/krs/{id}', [DosenController::class, 'krsUpdate'])->name('krs.update');
    Route::delete('/krs/{id}', [DosenController::class, 'krsDestroy'])->name('krs.destroy');
    Route::get('/krs/mahasiswa', [DosenController::class, 'krsMahasiswaIndex'])->name('krs.mahasiswa');
    
    // Pengumpulan (view + beri nilai)
    Route::get('/pengumpulan-tugas', [DosenController::class, 'pengumpulanTugasIndex'])->name('pengumpulan.tugas');
    Route::put('/pengumpulan-tugas/{id}', [DosenController::class, 'pengumpulanTugasUpdate'])->name('pengumpulan.tugas.update');
    Route::get('/pengumpulan-uts', [DosenController::class, 'pengumpulanUtsIndex'])->name('pengumpulan.uts');
    Route::put('/pengumpulan-uts/{id}', [DosenController::class, 'pengumpulanUtsUpdate'])->name('pengumpulan.uts.update');
    Route::get('/pengumpulan-uas', [DosenController::class, 'pengumpulanUasIndex'])->name('pengumpulan.uas');
    Route::put('/pengumpulan-uas/{id}', [DosenController::class, 'pengumpulanUasUpdate'])->name('pengumpulan.uas.update');
});

// ===== ROUTES MAHASISWA (VIEW + DOWNLOAD + UPLOAD) =====
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/krs', [MahasiswaController::class, 'krsIndex'])->name('krs.index');
    Route::get('/krs/download-pdf', [MahasiswaController::class, 'downloadKrsPdf'])->name('krs.download-pdf');
    
    Route::get('/materi', [MahasiswaController::class, 'materiIndex'])->name('materi.index');
    
    Route::get('/tugas', [MahasiswaController::class, 'tugasIndex'])->name('tugas.index');
    Route::post('/tugas/{id}/kumpulkan', [MahasiswaController::class, 'tugasSubmit'])->name('tugas.submit');
    
    Route::get('/uts', [MahasiswaController::class, 'utsIndex'])->name('uts.index');
    Route::post('/uts/{id}/absen', [MahasiswaController::class, 'utsAbsen'])->name('uts.absen');
    Route::post('/uts/{id}/kumpulkan', [MahasiswaController::class, 'utsSubmit'])->name('uts.submit');
    
    Route::get('/uas', [MahasiswaController::class, 'uasIndex'])->name('uas.index');
    Route::post('/uas/{id}/absen', [MahasiswaController::class, 'uasAbsen'])->name('uas.absen');
    Route::post('/uas/{id}/kumpulkan', [MahasiswaController::class, 'uasSubmit'])->name('uas.submit');
    
    Route::get('/absen', [MahasiswaController::class, 'absenIndex'])->name('absen.index');
    Route::post('/absen', [MahasiswaController::class, 'absenStore'])->name('absen.store');
    
    Route::get('/khs', [MahasiswaController::class, 'khsIndex'])->name('khs.index');
    Route::get('/khs/download-pdf', [MahasiswaController::class, 'downloadKhsPdf'])->name('khs.download-pdf');
});