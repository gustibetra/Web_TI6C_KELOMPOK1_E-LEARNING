@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="fas fa-list-alt"></i> Kartu Rencana Studi (KRS)</h3>
    <div>
        <a href="{{ route('mahasiswa.krs.download-pdf') }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Download KRS PDF
        </a>
    </div>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> <strong>Info:</strong> Berikut adalah mata kuliah yang telah dipaketkan untuk Anda. Silakan download KRS untuk mencetak.
</div>

<div class="card shadow-sm">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Mata Kuliah Yang Diambil</h5>
        <span class="badge bg-light text-dark">Total SKS: {{ $totalSks }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode MK</th>
                    <th width="35%">Nama MK</th>
                    <th width="10%">SKS</th>
                    <th width="20%">Kelas</th>
                    <th width="15%">Dosen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($krsData as $index => $krs)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge bg-secondary">{{ $krs->matakuliah->kode_matkul }}</span></td>
                    <td><strong>{{ $krs->matakuliah->nama_matkul }}</strong></td>
                    <td>{{ $krs->matakuliah->sks }} SKS</td>
                    <td>
                        <span class="badge bg-primary">
                            Kelas {{ $krs->matakuliah->kelas ?? $mahasiswa->kelas }}
                        </span>
                    </td>
                    <td>{{ $krs->matakuliah->dosen->nama_dosen }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="fas fa-exclamation-circle"></i> Belum ada mata kuliah yang dipaketkan untuk Anda
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($krsData->count() > 0)
            <tfoot class="table-light">
                <tr>
                    <th colspan="3" class="text-end">Total SKS Diambil:</th>
                    <th colspan="3">{{ $totalSks }} SKS</th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

<!-- Info Card -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-user-graduate"></i> Informasi Mahasiswa
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td width="30%"><strong>Nama</strong></td>
                        <td>: {{ $mahasiswa->nama_mahasiswa }}</td>
                    </tr>
                    <tr>
                        <td><strong>NPM</strong></td>
                        <td>: {{ $mahasiswa->id_user }}</td>
                    </tr>
                    <tr>
                        <td><strong>Program Studi</strong></td>
                        <td>: {{ $mahasiswa->prodi }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kelas</strong></td>
                        <td>: {{ $mahasiswa->kelas }}</td>
                    </tr>
                    <tr>
                        <td><strong>Semester</strong></td>
                        <td>: {{ $mahasiswa->semester }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <i class="fas fa-info-circle"></i> Petunjuk
            </div>
            <div class="card-body">
                <ol class="mb-0">
                    <li>Mata kuliah di atas sudah dipaketkan untuk Anda</li>
                    <li>Klik tombol <strong>"Download KRS PDF"</strong> untuk mencetak</li>
                    <li>Periksa kembali data mata kuliah yang tertera</li>
                    <li>Hubungi admin jika ada kesalahan data</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection