@extends('layouts.dosen')
@section('title', 'Rekap Absensi')
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
<h3><i class="fas fa-user-check"></i> Rekap Absensi Mahasiswa</h3>
@if(!$sesiAktif)
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBukaSesi">
<i class="fas fa-play-circle"></i> Buka Sesi Absensi
</button>
@endif
</div>
@if($sesiAktif)
<div class="alert alert-success alert-dismissible fade show" role="alert">
<h5 class="alert-heading"><i class="fas fa-check-circle"></i> Sesi Absensi Sedang Aktif!</h5>
<p class="mb-1"><strong>Mata Kuliah:</strong>
{{ \App\Models\Matakuliah::find($sesiAktif['id_matkul'])->nama_matkul ?? '-' }}
</p>
<p class="mb-1"><strong>Waktu Buka:</strong> {{ $sesiAktif['waktu_buka'] }}</p>
<p class="mb-1"><strong>Waktu Tutup:</strong> {{ $sesiAktif['waktu_tutup'] }}</p>
<p class="mb-2"><strong>Sisa Waktu:</strong> <span id="countdown" class="badge bg-warning text-dark fs-6"></span></p>
<form action="{{ route('dosen.absen.tutup-sesi') }}" method="POST" class="d-inline">
@csrf
<input type="hidden" name="id_matkul" value="{{ $sesiAktif['id_matkul'] }}">
<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tutup sesi absensi sekarang?')">
<i class="fas fa-stop-circle"></i> Tutup Sesi
</button>
</form>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@else
<div class="alert alert-info">
<i class="fas fa-info-circle"></i> Klik tombol <strong>"Buka Sesi Absensi"</strong> untuk memulai sesi absensi mahasiswa.
</div>
@endif
<!-- Riwayat Sesi -->
@if(isset($riwayatSesi) && count($riwayatSesi) > 0)
<div class="card shadow-sm mb-4">
<div class="card-header bg-secondary text-white">
<h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Sesi Absensi</h5>
</div>
<div class="card-body">
<div class="table-responsive">
<table class="table table-sm table-hover mb-0">
<thead class="table-light">
<tr>
<th>Mata Kuliah</th>
<th>Tanggal</th>
<th>Waktu</th>
<th>Durasi</th>
<th>Status</th>
</tr>
</thead>
<tbody>
@foreach($riwayatSesi as $sesi)
<tr>
<td>{{ \App\Models\Matakuliah::find($sesi['id_matkul'])->nama_matkul ?? '-' }}</td>
<td>{{ $sesi['tanggal'] }}</td>
<td>{{ $sesi['waktu_buka'] }} - {{ $sesi['waktu_tutup'] }}</td>
<td>{{ $sesi['durasi_menit'] }} menit</td>
<td>
@if($sesi['status'] == 'aktif')
<span class="badge bg-success">Aktif</span>
@else
<span class="badge bg-secondary">Nonaktif</span>
@endif
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>
</div>
@endif
<!-- Daftar Absensi -->
<div class="card shadow-sm">
<div class="card-header bg-primary text-white">
<h5 class="mb-0"><i class="fas fa-list"></i> Daftar Absensi Mahasiswa</h5>
</div>
<div class="table-responsive">
<table class="table table-hover mb-0">
<thead class="table-light">
<tr>
<th>No</th>
<th>Mahasiswa</th>
<th>Mata Kuliah</th>
<th>Tanggal</th>
<th>Foto</th>
<th>Status</th>
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
<td>{{ $item->tanggal_absen }}</td>
<td>
@if($item->foto_pash && $item->foto_pash !== 'manual_absen.jpg')
<img src="{{ asset('storage/' . $item->foto_pash) }}" width="50" class="rounded">
@else
<span class="badge bg-info">Manual</span>
@endif
</td>
<td>
@php $color = $item->status === 'hadir' ? 'success' : ($item->status === 'tidak_hadir' ? 'danger' : 'warning'); @endphp
<span class="badge bg-{{ $color }}">{{ $item->status }}</span>
</td>
<td>
<form action="{{ route('dosen.absen.update', $item->id_absen) }}" method="POST" class="d-flex gap-1">
@csrf @method('PUT')
<select name="status" class="form-select form-select-sm">
<option value="hadir" {{ $item->status === 'hadir' ? 'selected' : '' }}>Hadir</option>
<option value="tidak_hadir" {{ $item->status === 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
<option value="pending" {{ $item->status === 'pending' ? 'selected' : '' }}>Pending</option>
</select>
<button class="btn btn-sm btn-primary"><i class="fas fa-save"></i></button>
</form>
</td>
</tr>
@empty
<tr><td colspan="7" class="text-center py-4">Belum ada data absensi</td></tr>
@endforelse
</tbody>
</table>
</div>
</div>
<!-- Modal Buka Sesi -->
<div class="modal fade" id="modalBukaSesi" tabindex="-1">
<div class="modal-dialog">
<form action="{{ route('dosen.absen.buka-sesi') }}" method="POST">
@csrf
<div class="modal-content">
<div class="modal-header bg-primary text-white">
<h5 class="modal-title"><i class="fas fa-play-circle"></i> Buka Sesi Absensi</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<div class="mb-3">
<label class="form-label">Mata Kuliah</label>
<select name="id_matkul" class="form-select" required>
<option value="">-- Pilih Mata Kuliah --</option>
@foreach(\App\Models\Matakuliah::where('id_dosen', $dosen->id_dosen)->get() as $mk)
<option value="{{ $mk->id_matkul }}">{{ $mk->nama_matkul }}</option>
@endforeach
</select>
</div>
<div class="mb-3">
<label class="form-label">Durasi Sesi (menit)</label>
<select name="durasi_menit" class="form-select" required>
<option value="15">15 menit</option>
<option value="30" selected>30 menit</option>
<option value="45">45 menit</option>
<option value="60">60 menit</option>
<option value="90">90 menit</option>
</select>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button type="submit" class="btn btn-primary"><i class="fas fa-play"></i> Buka Sesi</button>
</div>
</div>
</form>
</div>
</div>
@endsection
@section('scripts')
@if($sesiAktif)
<script>
function updateCountdown() {
const waktuTutup = {{ $sesiAktif['berakhir_di'] }} * 1000;
const now = Date.now();
const diff = waktuTutup - now;
if (diff > 0) {
const minutes = Math.floor(diff / 60000);
const seconds = Math.floor((diff % 60000) / 1000);
document.getElementById('countdown').textContent = `${minutes}m ${seconds}s`;
} else {
document.getElementById('countdown').textContent = 'Waktu Habis';
setTimeout(() => location.reload(), 2000);
}
}
setInterval(updateCountdown, 1000);
updateCountdown();
</script>
@endif
@endsection