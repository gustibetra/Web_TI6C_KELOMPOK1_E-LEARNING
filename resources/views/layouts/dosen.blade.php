@extends('layouts.app')
@section('title', 'Dashboard Dosen')

@section('sidebar')
<div class="col-md-3 sidebar">
    <div class="list-group list-group-flush py-3">
        <a href="{{ route('dosen.dashboard') }}" class="list-group-item {{ request()->is('dosen/dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('dosen.matakuliah.index') }}" class="list-group-item {{ request()->is('dosen/matakuliah*') ? 'active' : '' }}">
            <i class="fas fa-book"></i> Matakuliah
        </a>
        <a href="{{ route('dosen.materi.index') }}" class="list-group-item {{ request()->is('dosen/materi*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i> Materi
        </a>
        <a href="{{ route('dosen.tugas.index') }}" class="list-group-item {{ request()->is('dosen/tugas*') ? 'active' : '' }}">
            <i class="fas fa-tasks"></i> Tugas
        </a>
        <a href="{{ route('dosen.uts.index') }}" class="list-group-item {{ request()->is('dosen/uts*') ? 'active' : '' }}">
            <i class="fas fa-edit"></i> UTS
        </a>
        <a href="{{ route('dosen.uas.index') }}" class="list-group-item {{ request()->is('dosen/uas*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-check"></i> UAS
        </a>
        <a href="{{ route('dosen.absen.index') }}" class="list-group-item {{ request()->is('dosen/absen*') ? 'active' : '' }}">
            <i class="fas fa-user-check"></i> Absensi
        </a>
        <a href="{{ route('dosen.krs.index') }}" class="list-group-item {{ request()->is('dosen/krs*') ? 'active' : '' }}">
            <i class="fas fa-list-alt"></i> KRS Mahasiswa
        </a>
        <a href="{{ route('dosen.khs.index') }}" class="list-group-item {{ request()->is('dosen/khs*') ? 'active' : '' }}">
            <i class="fas fa-star"></i> KHS / Nilai
        </a>
        <a href="{{ route('dosen.pengumpulan.tugas') }}" class="list-group-item {{ request()->is('dosen/pengumpulan-tugas*') ? 'active' : '' }}">
            <i class="fas fa-inbox"></i> Pengumpulan Tugas
        </a>
        <a href="{{ route('dosen.pengumpulan.uts') }}" class="list-group-item {{ request()->is('dosen/pengumpulan-uts*') ? 'active' : '' }}">
            <i class="fas fa-inbox"></i> Pengumpulan UTS
        </a>
        <a href="{{ route('dosen.pengumpulan.uas') }}" class="list-group-item {{ request()->is('dosen/pengumpulan-uas*') ? 'active' : '' }}">
            <i class="fas fa-inbox"></i> Pengumpulan UAS
        </a>
        
        <hr class="my-2">
        <a href="{{ route('dosen.profile') }}" class="list-group-item {{ request()->is('profile-dosen') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> Profil Saya
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <h2>Selamat Datang, Dr. Budi Hartono</h2>
    
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Matakuliah Diampu</h5>
                    <h2>2</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Materi</h5>
                    <h2>4</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Total Tugas</h5>
                    <h2>4</h2>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection