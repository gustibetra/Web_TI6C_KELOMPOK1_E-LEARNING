@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="fas fa-star"></i> Kartu Hasil Studi (KHS)</h3>
    <a href="{{ route('mahasiswa.khs.download-pdf') }}" class="btn btn-danger" target="_blank">
        <i class="fas fa-file-pdf"></i> Download KHS PDF
    </a>
</div>

@if($data->count() > 0)
<!-- Summary Card -->
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5 class="card-title">Total SKS</h5>
                <h2 class="mb-0">{{ $totalSks }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5 class="card-title">IPK</h5>
                <h2 class="mb-0">{{ number_format($ipk, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5 class="card-title">Total Mata Kuliah</h5>
                <h2 class="mb-0">{{ $data->count() }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Tabel KHS -->
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Daftar Nilai</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode MK</th>
                    <th width="35%">Nama MK</th>
                    <th width="10%">SKS</th>
                    <th width="15%">Nilai Akhir</th>
                    <th width="10%">Grade</th>
                    <th width="10%">Bobot</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $khs)
                @php
                    $grade = strtoupper($khs->grade);
                    $bobot = 0;
                    switch($grade) {
                        case 'A': $bobot = 4.0; break;
                        case 'B+': $bobot = 3.5; break;
                        case 'B': $bobot = 3.0; break;
                        case 'C+': $bobot = 2.5; break;
                        case 'C': $bobot = 2.0; break;
                        case 'D': $bobot = 1.0; break;
                        case 'E': $bobot = 0.0; break;
                    }
                    
                    $badgeColor = match($grade) {
                        'A' => 'success',
                        'B+', 'B' => 'primary',
                        'C+', 'C' => 'warning',
                        'D' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge bg-secondary">{{ $khs->matakuliah->kode_matkul }}</span></td>
                    <td><strong>{{ $khs->matakuliah->nama_matkul }}</strong></td>
                    <td>{{ $khs->matakuliah->sks }}</td>
                    <td>{{ number_format($khs->nilai_akhir, 2) }}</td>
                    <td><span class="badge bg-{{ $badgeColor }}">{{ $grade }}</span></td>
                    <td>{{ number_format($bobot, 1) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="3" class="text-end">Total:</th>
                    <th><strong>{{ $totalSks }} SKS</strong></th>
                    <th colspan="2"><strong>IPK: {{ number_format($ipk, 2) }}</strong></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Info Card -->
<div class="alert alert-info mt-3">
    <i class="fas fa-info-circle"></i> <strong>Keterangan:</strong>
    <ul class="mb-0 mt-2">
        <li>IPK dihitung berdasarkan bobot nilai dan SKS</li>
        <li>Klik tombol <strong>"Download KHS PDF"</strong> untuk mencetak</li>
        <li>Hubungi admin jika ada kesalahan data nilai</li>
    </ul>
</div>

@else
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i> <strong>Belum Ada Nilai</strong><br>
    Nilai KHS Anda belum tersedia. Silakan hubungi dosen atau admin untuk informasi lebih lanjut.
</div>
@endif
@endsection