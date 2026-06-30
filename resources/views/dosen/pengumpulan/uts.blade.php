@extends('layouts.dosen')
@section('title', 'Pengumpulan UTS')
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
<h3 class="mb-4"><i class="fas fa-inbox"></i> Pengumpulan UTS</h3>
<div class="card shadow-sm">
<div class="card-body">
<div class="table-responsive">
<table class="table table-hover">
<thead>
<tr>
<th>No</th>
<th>Mahasiswa</th>
<th>Matakuliah</th>
<th>Link Jawaban</th>
<th>Tanggal</th>
<th>Nilai</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
@forelse($data as $index => $item)
<tr>
<td>{{ $index + 1 }}</td>
<td>
<strong>{{ $item->mahasiswa->nama_mahasiswa ?? 'Data tidak tersedia' }}</strong><br>
<small class="text-muted">{{ $item->mahasiswa->id_user ?? '-' }}</small>
</td>
<td>{{ $item->uts->matakuliah->nama_matkul ?? '-' }}</td>
<td>
@if($item->link_pengumpulan_uts)
@if(str_starts_with($item->link_pengumpulan_uts, 'http'))
<a href="{{ $item->link_pengumpulan_uts }}" target="_blank" class="btn btn-sm btn-info">
<i class="fab fa-google-drive"></i> Lihat
</a>
@else
<a href="{{ asset('storage/' . $item->link_pengumpulan_uts) }}" target="_blank" class="btn btn-sm btn-info">
<i class="fas fa-download"></i> Download
</a>
@endif
@else
<span class="text-muted">Tidak ada</span>
@endif
</td>
<td>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') : '-' }}</td>
<td>
@if($item->nilai_uts > 0)
<span class="badge bg-success">{{ $item->nilai_uts }}</span>
@else
<span class="badge bg-warning text-dark">Belum</span>
@endif
</td>
<td>
<button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalNilai{{ $item->id_up_uts }}">
<i class="fas fa-star"></i> Nilai
</button>
</td>
</tr>
@empty
<tr>
<td colspan="7" class="text-center py-4 text-muted">Belum ada pengumpulan UTS</td>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>
</div>
<!-- Modal Penilaian -->
@foreach($data as $item)
<div class="modal fade" id="modalNilai{{ $item->id_up_uts }}" tabindex="-1">
<div class="modal-dialog">
<form action="{{ route('dosen.pengumpulan.uts.update', $item->id_up_uts) }}" method="POST">
@csrf @method('PUT')
<div class="modal-content">
<div class="modal-header bg-warning">
<h5 class="modal-title"><i class="fas fa-star"></i> Beri Nilai UTS</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<p><strong>Mahasiswa:</strong> {{ $item->mahasiswa->nama_mahasiswa ?? 'N/A' }}</p>
<p><strong>UTS:</strong> {{ $item->uts->matakuliah->nama_matkul ?? 'N/A' }}</p>
<div class="mb-3">
<label class="form-label">Nilai (0-100)</label>
<input type="number" name="nilai_uts" class="form-control" value="{{ $item->nilai_uts }}" min="0" max="100" required>
</div>
<div class="mb-3">
<label class="form-label">Feedback</label>
<textarea name="feedback" class="form-control" rows="3">{{ $item->feedback }}</textarea>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button type="submit" class="btn btn-warning">Simpan Nilai</button>
</div>
</div>
</form>
</div>
</div>
@endforeach
@endsection