@extends('layouts.dosen')
@section('title', 'Daftar UTS')

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

<!-- Alert Sesi Absen UTS Aktif -->
@php
    $sesiAktifUts = null;
    $idMatkulList = \App\Models\Matakuliah::where('id_dosen', $dosen->id_dosen)->pluck('id_matkul');
    foreach ($idMatkulList as $idMk) {
        $sesi = cache("sesi_absen_uts_{$idMk}");
        if ($sesi && $sesi['status'] === 'aktif') {
            $sesiAktifUts = $sesi;
            $sesiAktifUts['id_matkul'] = $idMk;
            break;
        }
    }
@endphp

@if($sesiAktifUts)
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <h5 class="alert-heading"><i class="fas fa-check-circle"></i> Sesi Absensi UTS Sedang Aktif!</h5>
    <p class="mb-1"><strong>Mata Kuliah:</strong> 
        {{ \App\Models\Matakuliah::find($sesiAktifUts['id_matkul'])->nama_matkul ?? '-' }}
    </p>
    <p class="mb-1"><strong>Waktu Buka:</strong> {{ $sesiAktifUts['waktu_buka'] }}</p>
    <p class="mb-1"><strong>Waktu Tutup:</strong> {{ $sesiAktifUts['waktu_tutup'] }}</p>
    <p class="mb-2"><strong>Sisa Waktu:</strong> <span id="countdown" class="badge bg-warning text-dark fs-6"></span></p>
    <form action="{{ route('dosen.uts.tutup-sesi-absen') }}" method="POST" class="d-inline">
        @csrf
        <input type="hidden" name="id_matkul" value="{{ $sesiAktifUts['id_matkul'] }}">
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tutup sesi absensi UTS sekarang?')">
            <i class="fas fa-stop-circle"></i> Tutup Sesi Absen
        </button>
    </form>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-edit"></i> Daftar UTS</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUTS">
        <i class="fas fa-plus"></i> Tambah UTS
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
                        <th>Deadline</th>
                        <th>File Soal</th>
                        <th>Status Absen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    @php
                        $sesiUts = cache("sesi_absen_uts_{$item->id_matkul}");
                        $adaSesi = $sesiUts && $sesiUts['status'] === 'aktif';
                        
                        // Hitung jumlah mahasiswa yang sudah absen
                        $mahasiswaMatkul = \App\Models\Krs::where('id_matkul', $item->id_matkul)->pluck('id_mahasiswa');
                        $jumlahSudahAbsen = \App\Models\Absen::whereIn('id_mahasiswa', $mahasiswaMatkul)
                            ->where('id_matkul', $item->id_matkul)
                            ->count();
                        $jumlahTotalMhs = $mahasiswaMatkul->count();
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->matakuliah->nama_matkul }}</td>
                        <td><span class="badge bg-danger">{{ $item->deadline }}</span></td>
                        <td>
                            <a href="{{ asset('storage/' . $item->soal_uts) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </td>
                        <td>
                            @if($adaSesi)
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Absen Dibuka</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-times-circle"></i> Belum</span>
                            @endif
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-users"></i> {{ $jumlahSudahAbsen }}/{{ $jumlahTotalMhs }} mahasiswa
                            </small>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalAbsensi{{ $item->id_uts }}">
                                <i class="fas fa-users"></i> Absensi
                            </button>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id_uts }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            @if(!$adaSesi)
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalBukaAbsen{{ $item->id_uts }}">
                                <i class="fas fa-camera"></i> Buka Absen
                            </button>
                            @endif
                            <form action="{{ route('dosen.uts.destroy', $item->id_uts) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Belum ada UTS</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah UTS -->
<div class="modal fade" id="modalTambahUTS" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('dosen.uts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah UTS</h5>
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
                        <label class="form-label">Deadline UTS</label>
                        <input type="datetime-local" name="deadline" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload File Soal (PDF/DOC)</label>
                        <input type="file" name="soal" class="form-control" accept=".pdf,.doc,.docx" required>
                    </div>
                    
                    <hr>
                    <h6><i class="fas fa-camera"></i> Sesi Absensi UTS</h6>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="buka_absen" id="bukaAbsenTambah" value="1">
                        <label class="form-check-label" for="bukaAbsenTambah">
                            <strong>Buka sesi absensi setelah UTS dibuat</strong>
                        </label>
                    </div>
                    <div class="mb-3" id="durasiAbsenTambah" style="display: none;">
                        <label class="form-label">Durasi Sesi Absensi (menit)</label>
                        <select name="durasi_absen" class="form-select">
                            <option value="15">15 menit</option>
                            <option value="30" selected>30 menit</option>
                            <option value="45">45 menit</option>
                            <option value="60">60 menit</option>
                        </select>
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

<!-- Modal Edit UTS, Modal Buka Absen, dan Modal Absensi -->
@foreach($data as $item)
<!-- Modal Edit UTS -->
<div class="modal fade" id="modalEdit{{ $item->id_uts }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('dosen.uts.update', $item->id_uts) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit UTS</h5>
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
                        <label class="form-label">Deadline</label>
                        <input type="datetime-local" name="deadline" class="form-control" value="{{ $item->deadline }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ganti File Soal (Opsional)</label>
                        <input type="file" name="soal" class="form-control" accept=".pdf,.doc,.docx">
                        <small class="text-muted">File saat ini: {{ $item->soal_uts }}</small>
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

<!-- Modal Buka Absensi UTS -->
<div class="modal fade" id="modalBukaAbsen{{ $item->id_uts }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('dosen.uts.update', $item->id_uts) }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="id_matkul" value="{{ $item->id_matkul }}">
            <input type="hidden" name="deadline" value="{{ $item->deadline }}">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-camera"></i> Buka Absensi UTS</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Mata Kuliah:</strong> {{ $item->matakuliah->nama_matkul }}</p>
                    <div class="mb-3">
                        <label class="form-label">Durasi Sesi Absensi (menit)</label>
                        <select name="durasi_absen" class="form-select" required>
                            <option value="15">15 menit</option>
                            <option value="30" selected>30 menit</option>
                            <option value="45">45 menit</option>
                            <option value="60">60 menit</option>
                        </select>
                    </div>
                    <input type="hidden" name="buka_absen" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-play"></i> Buka Sesi</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL ABSENSI UTS - INI YANG SEBELUMNYA HILANG -->
<!-- ============================================ -->
@php
    $mahasiswaMatkul = \App\Models\Krs::where('id_matkul', $item->id_matkul)->with('mahasiswa')->get();
    $sudahAbsenIds = \App\Models\Absen::where('id_matkul', $item->id_matkul)
        ->whereIn('id_mahasiswa', $mahasiswaMatkul->pluck('id_mahasiswa'))
        ->pluck('id_mahasiswa')
        ->toArray();
    
    $dataAbsen = [];
    $belumAbsen = [];
    foreach ($mahasiswaMatkul as $krs) {
        $mhs = $krs->mahasiswa;
        if (in_array($mhs->id_mahasiswa, $sudahAbsenIds)) {
            $absen = \App\Models\Absen::where('id_mahasiswa', $mhs->id_mahasiswa)
                ->where('id_matkul', $item->id_matkul)
                ->first();
            $dataAbsen[] = ['mahasiswa' => $mhs, 'absen' => $absen];
        } else {
            $belumAbsen[] = $mhs;
        }
    }
@endphp

<div class="modal fade" id="modalAbsensi{{ $item->id_uts }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-users"></i> Absensi UTS - {{ $item->matakuliah->nama_matkul }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Statistik -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="alert alert-success mb-0">
                            <strong><i class="fas fa-check-circle"></i> Sudah Absen:</strong> 
                            {{ count($dataAbsen) }} mahasiswa
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-warning mb-0">
                            <strong><i class="fas fa-exclamation-triangle"></i> Belum Absen:</strong> 
                            {{ count($belumAbsen) }} mahasiswa
                        </div>
                    </div>
                </div>
                
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" id="absenTab{{ $item->id_uts }}" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="sudah-tab{{ $item->id_uts }}" 
                                data-bs-toggle="tab" data-bs-target="#sudah{{ $item->id_uts }}" 
                                type="button">
                            <i class="fas fa-check"></i> Sudah Absen ({{ count($dataAbsen) }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="belum-tab{{ $item->id_uts }}" 
                                data-bs-toggle="tab" data-bs-target="#belum{{ $item->id_uts }}" 
                                type="button">
                            <i class="fas fa-clock"></i> Belum Absen ({{ count($belumAbsen) }})
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="absenTabContent{{ $item->id_uts }}">
                    <!-- Tab Sudah Absen -->
                    <div class="tab-pane fade show active" id="sudah{{ $item->id_uts }}" role="tabpanel">
                        <div class="table-responsive mt-3">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>NPM</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Waktu Absen</th>
                                        <th>Tipe</th>
                                        <th>Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dataAbsen as $idx => $absenItem)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td><span class="badge bg-secondary">{{ $absenItem['mahasiswa']->id_user }}</span></td>
                                        <td><strong>{{ $absenItem['mahasiswa']->nama_mahasiswa }}</strong></td>
                                        <td>{{ $absenItem['mahasiswa']->kelas }}</td>
                                        <td>{{ \Carbon\Carbon::parse($absenItem['absen']->tanggal_absen)->format('d M Y H:i') }}</td>
                                        <td>
                                            @if($absenItem['absen']->foto_pash === 'manual_absen.jpg')
                                                <span class="badge bg-info">Manual</span>
                                            @else
                                                <span class="badge bg-success">Online</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($absenItem['absen']->foto_pash !== 'manual_absen.jpg')
                                                <a href="{{ asset('storage/' . $absenItem['absen']->foto_pash) }}" 
                                                   target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-image"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Belum ada mahasiswa yang absen</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Tab Belum Absen -->
                    <div class="tab-pane fade" id="belum{{ $item->id_uts }}" role="tabpanel">
                        <div class="table-responsive mt-3">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>NPM</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($belumAbsen as $idx => $mhs)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td><span class="badge bg-secondary">{{ $mhs->id_user }}</span></td>
                                        <td><strong>{{ $mhs->nama_mahasiswa }}</strong></td>
                                        <td>{{ $mhs->kelas }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalManual{{ $item->id_uts }}_{{ $mhs->id_mahasiswa }}">
                                                <i class="fas fa-check"></i> Absen Manual
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Semua mahasiswa sudah absen</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Absen Manual untuk setiap mahasiswa yang belum absen -->
@foreach($belumAbsen as $mhs)
<div class="modal fade" id="modalManual{{ $item->id_uts }}_{{ $mhs->id_mahasiswa }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('dosen.uts.absensi.manual', $item->id_uts) }}" method="POST">
            @csrf
            <input type="hidden" name="id_mahasiswa" value="{{ $mhs->id_mahasiswa }}">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Absen Manual: {{ $mhs->nama_mahasiswa }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengabsen mahasiswa ini secara manual?</p>
                    <div class="alert alert-info">
                        <strong>Nama:</strong> {{ $mhs->nama_mahasiswa }}<br>
                        <strong>NPM:</strong> {{ $mhs->id_user }}<br>
                        <strong>Kelas:</strong> {{ $mhs->kelas }}
                    </div>
                    <small class="text-muted">Absen manual akan ditandai tanpa foto bukti</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Absen Manual
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach
@endforeach

@endsection

@section('scripts')
<script>
document.getElementById('bukaAbsenTambah')?.addEventListener('change', function() {
    const durasiDiv = document.getElementById('durasiAbsenTambah');
    if (this.checked) {
        durasiDiv.style.display = 'block';
    } else {
        durasiDiv.style.display = 'none';
    }
});

@if($sesiAktifUts)
function updateCountdown() {
    const waktuTutup = {{ $sesiAktifUts['berakhir_di'] }} * 1000;
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
@endif
</script>
@endsection