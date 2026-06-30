@extends('layouts.dosen')
@section('title', 'Daftar Tugas')

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

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-tasks"></i> Daftar Tugas</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTugas">
        <i class="fas fa-plus"></i> Tambah Tugas
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Matakuliah</th>
                        <th>Judul</th>
                        <th>Deadline</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->matakuliah->nama_matkul }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->deadline }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id_tugas }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('dosen.tugas.destroy', $item->id_tugas) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada tugas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Tugas -->
<div class="modal fade" id="modalTambahTugas" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('dosen.tugas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Tugas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
                        <label class="form-label">Judul Tugas</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deadline</label>
                        <input type="datetime-local" name="deadline" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Soal Tugas (PDF/DOC)</label>
                        <input type="file" name="soal" class="form-control" accept=".pdf,.doc,.docx" required>
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

<!-- Modal Edit Tugas -->
@foreach($data as $item)
<div class="modal fade" id="modalEdit{{ $item->id_tugas }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('dosen.tugas.update', $item->id_tugas) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Matakuliah</label>
                        <select name="id_matkul" class="form-select" required>
                            @foreach($matakuliahs as $mk)
                                <option value="{{ $mk->id_matkul }}" {{ $mk->id_matkul == $item->id_matkul ? 'selected' : '' }}>{{ $mk->nama_matkul }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Tugas</label>
                        <input type="text" name="judul" class="form-control" value="{{ $item->judul }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" required>{{ $item->deskripsi }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deadline</label>
                        <input type="datetime-local" name="deadline" class="form-control" value="{{ $item->deadline }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ganti Soal (Opsional)</label>
                        <input type="file" name="soal" class="form-control" accept=".pdf,.doc,.docx">
                        <small class="text-muted">File saat ini: {{ $item->soal_tugas }}</small>
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