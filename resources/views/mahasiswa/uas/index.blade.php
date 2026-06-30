@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="fas fa-clipboard-check"></i> Ujian Akhir Semester (UAS)</h3>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="alert alert-info mb-3">
    <h5 class="alert-heading"><i class="fas fa-info-circle"></i> Alur Pengerjaan UAS:</h5>
    <ol class="mb-0">
        <li><strong>Langkah 1:</strong> Klik tombol "Absen UAS" dan upload foto bukti kehadiran (selfie di kelas).</li>
        <li><strong>Langkah 2:</strong> Setelah absen berhasil, tombol "Download Soal" akan muncul.</li>
        <li><strong>Langkah 3:</strong> Kerjakan soal, upload jawaban ke Google Drive, dan kumpulkan link-nya.</li>
        <li><strong>Langkah 4:</strong> Anda juga bisa memberikan kritik dan saran untuk mata kuliah ini.</li>
    </ol>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> Daftar UAS</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>Matakuliah</th>
                    <th>Deadline</th>
                    <th>Soal</th>
                    <th>Status Absen</th>
                    <th>Status UAS</th>
                    <th width="25%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($uas as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $item->matakuliah->nama_matkul }}</strong></td>
                    <td><span class="badge bg-danger">{{ $item->deadline }}</span></td>
                    <td>
                        @if($item->sudah_absen)
                            <a href="{{ asset('storage/' . $item->soal_uas) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-download"></i> Download
                            </a>
                        @else
                            <span class="text-muted"><i class="fas fa-lock"></i> Terkunci</span>
                        @endif
                    </td>
                    <td>
                        @if($item->sudah_absen)
                            <span class="badge bg-success"><i class="fas fa-check"></i> Hadir</span>
                        @else
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Belum Absen</span>
                        @endif
                    </td>
                    <td>
                        @if($item->sudah_kumpul)
                            <span class="badge bg-success"><i class="fas fa-check"></i> Dikumpulkan</span>
                        @else
                            <span class="badge bg-secondary">Belum</span>
                        @endif
                    </td>
                    <td>
                        @if(!$item->sudah_absen)
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAbsen{{ $item->id_uas }}">
                                <i class="fas fa-camera"></i> Absen UAS
                            </button>
                        @elseif(!$item->sudah_kumpul)
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalKumpul{{ $item->id_uas }}">
                                <i class="fab fa-google-drive"></i> Kumpulkan
                            </button>
                        @else
                            <span class="text-muted small">Selesai ✓</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada jadwal UAS</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Absensi UAS -->
@foreach($uas as $item)
@if(!$item->sudah_absen)
<div class="modal fade" id="modalAbsen{{ $item->id_uas }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('mahasiswa.uas.absen', $item->id_uas) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-camera"></i> Absensi UAS: {{ $item->matakuliah->nama_matkul }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Silakan upload foto bukti kehadiran Anda di kelas untuk membuka soal UAS.</p>
                    <div class="mb-3">
                        <label class="form-label"><strong>Foto Bukti Kehadiran (Selfie)</strong></label>
                        <input type="file" class="form-control" name="foto_bukti" accept="image/*" required>
                        <small class="text-muted">Format: JPG/PNG, Maksimal 2MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Kirim Absen</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Modal Kumpulkan UAS (Hanya muncul jika sudah absen) -->
@if($item->sudah_absen && !$item->sudah_kumpul)
<div class="modal fade" id="modalKumpul{{ $item->id_uas }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('mahasiswa.uas.submit', $item->id_uas) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fab fa-google-drive"></i> Kumpulkan Jawaban UAS</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Matakuliah:</strong> {{ $item->matakuliah->nama_matkul }}<br>
                        <strong>Deadline:</strong> {{ $item->deadline }}
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Link Google Drive Jawaban</strong></label>
                        <input type="url" class="form-control" name="link_google_drive" placeholder="https://drive.google.com/..." required>
                        <small class="text-muted">Pastikan akses link diatur ke "Anyone with the link"</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Kritik dan Saran (Opsional)</strong></label>
                        <textarea class="form-control" name="kritik_dan_saran" rows="3" placeholder="Berikan kritik dan saran Anda untuk mata kuliah ini..."></textarea>
                        <small class="text-muted">Kritik dan saran Anda sangat berharga untuk perbaikan pembelajaran</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Kumpulkan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach
@endsection