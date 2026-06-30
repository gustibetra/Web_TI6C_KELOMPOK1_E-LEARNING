@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<h2 class="mb-4">Selamat Datang, {{ $mahasiswa->nama_mahasiswa }}</h2>
<p class="text-muted">Prodi: {{ $mahasiswa->prodi }} | Kelas: {{ $mahasiswa->kelas }} | Semester: {{ $mahasiswa->semester }}</p>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card card-stat bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h6 class="card-title">Matakuliah Diambil</h6><h2 class="mb-0">{{ $jumlahKrs }}</h2></div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h6 class="card-title">Total Absensi</h6><h2 class="mb-0">{{ $jumlahAbsen }}</h2></div>
                    <i class="fas fa-user-check fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection