@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<h3 class="mb-3"><i class="fas fa-tasks"></i> Daftar Tugas</h3>

<!-- CARD PETUNJUK - TAMBAHKAN DI SINI -->
<div class="alert alert-info mb-3">
    <h5 class="alert-heading"><i class="fas fa-info-circle"></i> Cara Pengumpulan Tugas:</h5>
    <ol class="mb-0">
        <li>Download soal tugas yang tersedia</li>
        <li>Kerjakan tugas sesuai petunjuk</li>
        <li>Upload hasil tugas ke <strong>Google Drive</strong> Anda</li>
        <li>Pastikan link sharing diatur ke <strong>"Anyone with the link can view"</strong></li>
        <li>Salin link Google Drive dan tempel di form pengumpulan</li>
    </ol>
</div>
<!-- AKHIR CARD PETUNJUK -->

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Matakuliah</th><th>Judul</th><th>Deskripsi</th><th>Deadline</th><th>Soal</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($tugas as $item)
                <tr>
                    <td>{{ $item->matakuliah->nama_matkul }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                    <td><span class="badge bg-danger">{{ $item->deadline }}</span></td>
                    <td><a href="{{ asset('storage/' . $item->soal_tugas) }}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-download"></i></a></td>
                    <td>
                        @if($item->sudah_kumpul)
                            <span class="badge bg-success">Sudah Dikumpulkan</span>
                        @else
                            <span class="badge bg-warning">Belum</span>
                        @endif
                    </td>
                    <td>
                        @if(!$item->sudah_kumpul)
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal{{ $item->id_tugas }}"><i class="fas fa-upload"></i> Kumpulkan</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4">Belum ada tugas</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@foreach($tugas as $item)
@if(!$item->sudah_kumpul)
<div class="modal fade" id="modal{{ $item->id_tugas }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('mahasiswa.tugas.submit', $item->id_tugas) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fab fa-google-drive"></i> Kumpulkan Tugas via Google Drive</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Tugas:</strong> {{ $item->judul }}<br>
                        <strong>Deadline:</strong> {{ $item->deadline }}
                    </div>
                    
                    <div class="mb-3">
                        <label for="link_gd{{ $item->id_tugas }}" class="form-label">
                            <i class="fas fa-link"></i> Link Google Drive
                        </label>
                        <input type="url" 
                               class="form-control" 
                               id="link_gd{{ $item->id_tugas }}" 
                               name="link_google_drive" 
                               placeholder="https://drive.google.com/file/d/..." 
                               required>
                        <small class="text-muted">
                            Upload file Anda ke Google Drive, lalu salin link-nya di sini
                        </small>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>Pastikan:</strong>
                        <ul class="mb-0 mt-2">
                            <li>File sudah diupload ke Google Drive</li>
                            <li>Link dapat diakses (sharing: Anyone with the link)</li>
                            <li>Format file sesuai yang diminta</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Kumpulkan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach
@endsection