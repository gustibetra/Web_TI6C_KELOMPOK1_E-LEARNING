@extends('layouts.app')
@section('title', 'Dashboard Mahasiswa')

@section('sidebar')
<div class="col-md-3 sidebar">
    <div class="list-group list-group-flush py-3">
        <a href="{{ route('mahasiswa.dashboard') }}" class="list-group-item {{ request()->is('mahasiswa/dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('mahasiswa.krs.index') }}" class="list-group-item {{ request()->is('mahasiswa/krs*') ? 'active' : '' }}">
            <i class="fas fa-list-alt"></i> KRS
        </a>
        <a href="{{ route('mahasiswa.materi.index') }}" class="list-group-item {{ request()->is('mahasiswa/materi*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i> Materi
        </a>
        <a href="{{ route('mahasiswa.tugas.index') }}" class="list-group-item {{ request()->is('mahasiswa/tugas*') ? 'active' : '' }}">
            <i class="fas fa-tasks"></i> Tugas
        </a>
        <a href="{{ route('mahasiswa.uts.index') }}" class="list-group-item {{ request()->is('mahasiswa/uts*') ? 'active' : '' }}">
            <i class="fas fa-edit"></i> UTS
        </a>
        <a href="{{ route('mahasiswa.uas.index') }}" class="list-group-item {{ request()->is('mahasiswa/uas*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-check"></i> UAS
        </a>
        <a href="{{ route('mahasiswa.absen.index') }}" class="list-group-item {{ request()->is('mahasiswa/absen*') ? 'active' : '' }}">
            <i class="fas fa-user-check"></i> Absensi
        </a>
        <a href="{{ route('mahasiswa.khs.index') }}" class="list-group-item {{ request()->is('mahasiswa/khs*') ? 'active' : '' }}">
            <i class="fas fa-star"></i> KHS
        </a>
        
        <!-- MENU PROFIL - DITAMBAHKAN -->
        <hr class="my-2">
        <a href="{{ route('profile') }}" class="list-group-item {{ request()->is('mahasiswa/profile') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> Profil Saya
        </a>
    </div>
</div>
@endsection

@section('content')
<!-- Alert Notifikasi -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show m-3" role="alert">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i> <strong>Terjadi kesalahan:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@yield('mahasiswa-content')
@endsection