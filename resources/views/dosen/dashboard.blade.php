@extends('layouts.dosen')
@section('dosen-content')
<h2 class="mb-4">Selamat Datang, {{ $dosen->nama_dosen }}</h2>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card card-stat bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Matakuliah Diampu</h6>
                        <h2 class="mb-0">{{ $jumlahMatkul }}</h2>
                    </div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Materi</h6>
                        <h2 class="mb-0">{{ $jumlahMateri }}</h2>
                    </div>
                    <i class="fas fa-file-alt fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Tugas</h6>
                        <h2 class="mb-0">{{ $jumlahTugas }}</h2>
                    </div>
                    <i class="fas fa-tasks fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection