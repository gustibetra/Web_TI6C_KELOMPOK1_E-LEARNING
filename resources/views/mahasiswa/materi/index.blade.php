@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<h3 class="mb-3"><i class="fas fa-file-alt"></i> Materi Kuliah</h3>
<div class="row g-3">
    @forelse($data as $item)
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">{{ $item->matakuliah->nama_matkul }}</h5>
                <p class="card-text text-muted">{{ $item->deksripsi }}</p>
                <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-primary"><i class="fas fa-download"></i> Download Materi</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12"><div class="alert alert-info text-center">Belum ada materi</div></div>
    @endforelse
</div>
@endsection