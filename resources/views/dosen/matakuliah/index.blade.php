@extends('layouts.dosen')
@section('title', 'Daftar Matakuliah')
@section('sidebar')
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
@endsection
@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
<i class="fas fa-check-circle me-2"></i> {{ session('success') }}
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
<div class="d-flex justify-content-between align-items-center mb-4">
<h3><i class="fas fa-book"></i> Daftar Matakuliah</h3>
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
<i class="fas fa-plus"></i> Tambah Matakuliah
</button>
</div>
<div class="card shadow-sm">
<div class="card-body">
<div class="table-responsive">
<table class="table table-hover">
<thead>
<tr>
<th>No</th>
<th>Kode MK</th>
<th>Nama MK</th>
<th>SKS</th>
<th>Semester</th>
<th>Waktu</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
@forelse($data as $index => $item)
<tr>
<td>{{ $index + 1 }}</td>
<td><span class="badge bg-secondary">{{ $item->kode_matkul }}</span></td>
<td><strong>{{ $item->nama_matkul }}</strong></td>
<td>{{ $item->sks }} SKS</td>
<td>{{ $item->semester }}</td>
<td>{{ $item->waktu_matkul }}</td>
<td>
<button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id_matkul }}">
<i class="fas fa-edit"></i>
</button>
<form action="{{ route('dosen.matakuliah.destroy', $item->id_matkul) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
@csrf @method('DELETE')
<button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
</form>
</td>
</tr>
@empty
<tr>
<td colspan="7" class="text-center py-4 text-muted">Belum ada mata kuliah</td>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>
</div>
<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
<div class="modal-dialog">
<form action="{{ route('dosen.matakuliah.store') }}" method="POST">
@csrf
<div class="modal-content">
<div class="modal-header bg-primary text-white">
<h5 class="modal-title">Tambah Matakuliah</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<div class="mb-3">
<label class="form-label">Kode MK</label>
<input type="text" name="kode_matkul" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Nama MK</label>
<input type="text" name="nama_matkul" class="form-control" required>
</div>
<div class="row">
<div class="col-6 mb-3">
<label class="form-label">SKS</label>
<input type="number" name="sks" class="form-control" min="1" max="6" required>
</div>
<div class="col-6 mb-3">
<label class="form-label">Semester</label>
<input type="number" name="semester" class="form-control" min="1" max="8" required>
</div>
</div>
<div class="mb-3">
<label class="form-label">Waktu Kuliah</label>
<input type="text" name="waktu_matkul" class="form-control" placeholder="Senin 08:00-10:00" required>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button type="submit" class="btn btn-primary">Simpan</button>
</div>
</div>
</form>
</div>
</div>
<!-- Modal Edit -->
@foreach($data as $item)
<div class="modal fade" id="modalEdit{{ $item->id_matkul }}" tabindex="-1">
<div class="modal-dialog">
<form action="{{ route('dosen.matakuliah.update', $item->id_matkul) }}" method="POST">
@csrf @method('PUT')
<div class="modal-content">
<div class="modal-header bg-warning">
<h5 class="modal-title">Edit Matakuliah</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<div class="mb-3">
<label class="form-label">Kode MK</label>
<input type="text" name="kode_matkul" class="form-control" value="{{ $item->kode_matkul }}" required>
</div>
<div class="mb-3">
<label class="form-label">Nama MK</label>
<input type="text" name="nama_matkul" class="form-control" value="{{ $item->nama_matkul }}" required>
</div>
<div class="row">
<div class="col-6 mb-3">
<label class="form-label">SKS</label>
<input type="number" name="sks" class="form-control" value="{{ $item->sks }}" min="1" max="6" required>
</div>
<div class="col-6 mb-3">
<label class="form-label">Semester</label>
<input type="number" name="semester" class="form-control" value="{{ $item->semester }}" min="1" max="8" required>
</div>
</div>
<div class="mb-3">
<label class="form-label">Waktu Kuliah</label>
<input type="text" name="waktu_matkul" class="form-control" value="{{ $item->waktu_matkul }}" required>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button type="submit" class="btn btn-warning">Update</button>
</div>
</div>
</form>
</div>
</div>
@endforeach
@endsection