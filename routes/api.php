<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{
    AuthApiController, DosenApiController, MahasiswaApiController,
    MatakuliahApiController, MateriApiController, AbsenApiController,
    KrsApiController, KhsApiController, TugasApiController,
    PengumpulanTugasApiController, UtsApiController, PengumpulanUtsApiController,
    UasApiController, PengumpulanUasApiController
};

Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);

    Route::middleware('role:dosen')->prefix('dosen')->group(function () {
        Route::apiResource('matakuliah', MatakuliahApiController::class);
        Route::apiResource('materi', MateriApiController::class);
        Route::apiResource('tugas', TugasApiController::class);
        Route::apiResource('uts', UtsApiController::class);
        Route::apiResource('uas', UasApiController::class);
        Route::apiResource('absen', AbsenApiController::class);
        Route::apiResource('khs', KhsApiController::class);
    });

    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->group(function () {
        Route::apiResource('krs', KrsApiController::class);
        Route::apiResource('pengumpulan-tugas', PengumpulanTugasApiController::class);
        Route::apiResource('pengumpulan-uts', PengumpulanUtsApiController::class);
        Route::apiResource('pengumpulan-uas', PengumpulanUasApiController::class);
    });
});