@extends('layouts.dosen')
@section('title', 'KHS / Nilai')
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
<h3><i class="fas fa-star"></i> KHS / Nilai Mahasiswa</h3>
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
<i class="fas fa-plus"></i> Tambah Nilai
</button>
</div>
<div class="card shadow-sm">
<div class="card-body">
<div class="table-responsive">
<table class="table table-hover">
<thead>
<tr>
<th>No</th>
<th>Mahasiswa</th>
<th>Matakuliah</th>
<th>Nilai Akhir</th>
<th>Grade</th>
<th>IPK</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
@forelse($data as $index => $item)
<tr>
<td>{{ $index + 1 }}</td>
<td><strong>{{ $item->mahasiswa->nama_mahasiswa ?? '-' }}</strong><br>
<small class="text-muted">{{ $item->mahasiswa->id_user ?? '-' }}</small>
</td>
<td>{{ $item->matakuliah->nama_matkul ?? '-' }}</td>
<td><strong>{{ $item->nilai_akhir }}</strong></td>
<td><span class="badge bg-info">{{ $item->grade }}</span></td>
<td>{{ $item->ipk }}</td>
<td>
<button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id_khs }}">
<i class="fas fa-edit"></i>
</button>
<form action="{{ route('dosen.khs.destroy', $item->id_khs) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
@csrf @method('DELETE')
<button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
</form>
</td>
</tr>
@empty
<tr>
<td colspan="7" class="text-center py-4 text-muted">Belum ada data nilai</td>
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
<form action="{{ route('dosen.khs.store') }}" method="POST">
@csrf
<div class="modal-content">
<div class="modal-header bg-primary text-white">
<h5 class="modal-title">Tambah Nilai</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<div class="mb-3">
<label class="form-label">Mahasiswa</label>
<select name="id_mahasiswa" class="form-select" required>
<option value="">-- Pilih Mahasiswa --</option>
@foreach($mahasiswas as $m)
<option value="{{ $m->id_mahasiswa }}">{{ $m->nama_mahasiswa }} ({{ $m->id_user }})</option>
@endforeach
</select>
</div>
<div class="mb-3">
<label class="form-label">Matakuliah</label>
<select name="id_matkul" class="form-select" required>
<option value="">-- Pilih Matakuliah --</option>
@foreach($matakuliahs as $mk)
<option value="{{ $mk->id_matkul }}">{{ $mk->nama_matkul }}</option>
@endforeach
</select>
</div>
<div class="mb-3">
<label class="form-label">Nilai Akhir (0-100)</label>
<input type="number" name="nilai_akhir" class="form-control" min="0" max="100" step="0.01" required>
</div>
<div class="mb-3">
<label class="form-label">Grade</label>
<input type="text" name="grade" class="form-control" placeholder="A, B+, B, C+, C, D, E" required>
</div>
<div class="mb-3">
<label class="form-label">IPK (0-4)</label>
<input type="number" name="ipk" class="form-control" min="0" max="4" step="0.01" required>
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
<div class="modal fade" id="modalEdit{{ $item->id_khs }}" tabindex="-1">
<div class="modal-dialog">
<form action="{{ route('dosen.khs.update', $item->id_khs) }}" method="POST">
@csrf @method('PUT')
<div class="modal-content">
<div class="modal-header bg-warning">
<h5 class="modal-title">Edit Nilai</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<p><strong>{{ $item->mahasiswa->nama_mahasiswa ?? '-' }}</strong> - {{ $item->matakuliah->nama_matkul ?? '-' }}</p>
<div class="mb-3">
<label class="form-label">Nilai Akhir</label>
<input type="number" name="nilai_akhir" class="form-control" value="{{ $item->nilai_akhir }}" min="0" max="100" step="0.01" required>
</div>
<div class="mb-3">
<label class="form-label">Grade</label>
<input type="text" name="grade" class="form-control" value="{{ $item->grade }}" required>
</div>
<div class="mb-3">
<label class="form-label">IPK</label>
<input type="number" name="ipk" class="form-control" value="{{ $item->ipk }}" min="0" max="4" step="0.01" required>
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