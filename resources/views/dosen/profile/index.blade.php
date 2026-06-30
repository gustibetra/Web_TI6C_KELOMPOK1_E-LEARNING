@extends('layouts.dosen')
@section('dosen-content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
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

            <!-- Card Profil dengan Foto -->
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="position-relative d-inline-block mb-3">
                        @if($dosen->foto_profil)
                            <img src="{{ asset('storage/' . $dosen->foto_profil) }}" 
                                 alt="Foto Profil" 
                                 class="rounded-circle" 
                                 width="128" 
                                 height="128"
                                 style="object-fit: cover; border: 4px solid #0d6efd;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                 style="width: 128px; height: 128px; font-size: 48px; font-weight: bold; border: 4px solid #0d6efd;">
                                {{ strtoupper(substr($dosen->nama_dosen, 0, 1)) }}
                            </div>
                        @endif
                        <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle shadow" 
                                style="width: 40px; height: 40px;"
                                data-bs-toggle="modal" 
                                data-bs-target="#modalFotoProfil"
                                type="button">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <h4 class="mb-1 fw-bold">{{ $dosen->nama_dosen }}</h4>
                    <p class="text-muted mb-0">NIDN: {{ $dosen->nidn }}</p>
                </div>
            </div>

            <!-- Info Dosen -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-chalkboard-teacher"></i> Informasi Dosen</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small">Nama Lengkap</label>
                <p class="fw-bold mb-0">{{ $dosen->nama_dosen }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small">Email</label>
                <p class="fw-bold mb-0">{{ $dosen->email ?? '-' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small">No. HP</label>
                <p class="fw-bold mb-0">{{ $dosen->no_hp ?? '-' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small">NIDN</label>
                <p class="fw-bold mb-0">{{ $dosen->nidn }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small">Program Studi</label>
                <p class="fw-bold mb-0">{{ $dosen->prodi }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small">Status</label>
                <span class="badge bg-success px-3 py-2">Aktif</span>
            </div>
        </div>
    </div>
</div>

            <!-- Menu -->
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <button class="list-group-item list-group-item-action border-0 py-3 px-4" 
                            type="button"
                            data-bs-toggle="modal" 
                            data-bs-target="#modalUbahPassword">
                        <i class="fas fa-key me-3 text-primary"></i>
                        <span class="fw-medium">Ubah Kata Sandi</span>
                        <i class="fas fa-chevron-right float-end mt-1 text-muted"></i>
                    </button>
                    <hr class="m-0">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action border-0 py-3 px-4 w-100 text-start">
                            <i class="fas fa-sign-out-alt me-3 text-danger"></i>
                            <span class="fw-medium">Keluar</span>
                            <i class="fas fa-chevron-right float-end mt-1 text-muted"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Foto Profil -->
<div class="modal fade" id="modalFotoProfil" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('dosen.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title mb-0">Edit Profil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        @if($dosen->foto_profil)
                            <img src="{{ asset('storage/' . $dosen->foto_profil) }}" 
                                 alt="Foto Profil" 
                                 class="rounded-circle" 
                                 width="100" 
                                 height="100"
                                 style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 100px; height: 100px; font-size: 36px; font-weight: bold;">
                                {{ strtoupper(substr($dosen->nama_dosen, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Foto Profil Baru</label>
                        <input type="file" name="foto_profil" class="form-control" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Format: JPG/PNG, Max 2MB</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_dosen" class="form-control" value="{{ $dosen->nama_dosen }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $dosen->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">No. HP</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ $dosen->no_hp }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ubah Password -->
<div class="modal fade" id="modalUbahPassword" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('dosen.profile.password') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title mb-0"><i class="fas fa-key me-2"></i>Ubah Kata Sandi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Password minimal 8 karakter dan harus berbeda dengan password lama</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Lama</label>
                        <input type="password" name="password_lama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru</label>
                        <input type="password" name="password_baru" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="password_baru_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Ubah Password</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection