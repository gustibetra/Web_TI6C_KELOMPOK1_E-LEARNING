@extends('layouts.dosen')
@section('dosen-content')
<div class="mb-3">
    <a href="{{ route('dosen.uts.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="fas fa-user-check"></i> Absensi UTS - {{ $uts->matakuliah->nama_matkul }}</h3>
    <div>
        <span class="badge bg-success me-2">Hadir: {{ count($dataAbsen) }}</span>
        <span class="badge bg-warning text-dark">Belum: {{ count($belumAbsen) }}</span>
    </div>
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

<!-- Mahasiswa yang Sudah Absen -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-check-circle"></i> Mahasiswa yang Sudah Absen</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>NPM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Kelas</th>
                    <th>Waktu Absen</th>
                    <th>Status</th>
                    <th>Bukti Foto</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataAbsen as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge bg-secondary">{{ $item['mahasiswa']->id_user }}</span></td>
                    <td><strong>{{ $item['mahasiswa']->nama_mahasiswa }}</strong></td>
                    <td>{{ $item['mahasiswa']->kelas }}</td>
                    <td>{{ $item['absen']->tanggal_absen->format('d M Y H:i') }}</td>
                    <td>
                        @if($item['absen']->foto_pash === 'manual_absen.jpg')
                            <span class="badge bg-info">Manual</span>
                        @else
                            <span class="badge bg-success">Online</span>
                        @endif
                    </td>
                    <td>
                        @if($item['absen']->foto_pash !== 'manual_absen.jpg')
                            <a href="{{ asset('storage/' . $item['absen']->foto_pash) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-image"></i> Lihat
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada mahasiswa yang absen</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Mahasiswa yang Belum Absen -->
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Mahasiswa yang Belum Absen</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>NPM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Kelas</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($belumAbsen as $index => $mhs)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge bg-secondary">{{ $mhs->id_user }}</span></td>
                    <td><strong>{{ $mhs->nama_mahasiswa }}</strong></td>
                    <td>{{ $mhs->kelas }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalManual{{ $mhs->id_mahasiswa }}">
                            <i class="fas fa-check"></i> Absen Manual
                        </button>
                    </td>
                </tr>
                
                <!-- Modal Absen Manual -->
                <div class="modal fade" id="modalManual{{ $mhs->id_mahasiswa }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('dosen.uts.absensi.manual', $uts->id_uts) }}" method="POST">
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
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Semua mahasiswa sudah absen</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection