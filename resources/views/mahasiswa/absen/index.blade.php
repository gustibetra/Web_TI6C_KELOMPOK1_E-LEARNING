@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="fas fa-user-check"></i> Absensi</h3>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($sesiAktif)
<div class="alert alert-success alert-dismissible fade show">
    <h5 class="alert-heading"><i class="fas fa-check-circle"></i> Sesi Absensi Sedang Aktif!</h5>
    <p class="mb-1"><strong>Mata Kuliah:</strong> 
        {{ \App\Models\Matakuliah::find($sesiAktif['id_matkul'])->nama_matkul ?? '-' }}
    </p>
    <p class="mb-1"><strong>Waktu Tutup:</strong> {{ $sesiAktif['waktu_tutup'] }}</p>
    <p class="mb-0"><strong>Sisa Waktu:</strong> <span id="countdown" class="badge bg-warning text-dark"></span></p>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@else
<div class="alert alert-warning">
    <i class="fas fa-clock"></i> <strong>Belum Ada Sesi Absensi</strong><br>
    Dosen belum membuka sesi absensi. Silakan tunggu hingga dosen membuka sesi.
</div>
@endif

<!-- Form Absen (hanya muncul jika sesi aktif) -->
@if($sesiAktif)
<div class="card shadow-sm mb-3">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-camera"></i> Absen Sekarang</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('mahasiswa.absen.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_matkul" value="{{ $sesiAktif['id_matkul'] }}">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mata Kuliah</label>
                    <input type="text" class="form-control" 
                           value="{{ \App\Models\Matakuliah::find($sesiAktif['id_matkul'])->nama_matkul ?? '-' }}" 
                           readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto Selfie (JPG/PNG, max 2MB)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" onclick="return confirm('Kirim absensi?')">
                <i class="fas fa-paper-plane"></i> Kirim Absensi
            </button>
        </form>
    </div>
</div>
@endif

<!-- Riwayat Absensi -->
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Absensi Saya</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mata Kuliah</th>
                    <th>Tanggal</th>
                    <th>Foto</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ $item->matakuliah->nama_matkul }}</td>
                    <td>{{ $item->tanggal_absen }}</td>
                    <td><img src="{{ asset('storage/' . $item->foto_pash) }}" width="60" class="rounded"></td>
                    <td>
                        @php $color = $item->status === 'hadir' ? 'success' : ($item->status === 'tidak_hadir' ? 'danger' : 'warning'); @endphp
                        <span class="badge bg-{{ $color }}">{{ $item->status }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat absensi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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
        document.getElementById('countdown').textContent = 
            `${minutes}m ${seconds}s`;
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