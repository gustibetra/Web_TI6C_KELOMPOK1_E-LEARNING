@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm mt-5">
            <div class="card-body p-5">
                <h3 class="text-center mb-4"><i class="fas fa-graduation-cap text-primary"></i> Login E-Learning</h3>
                @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
                <form method="POST" action="/login">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">NIDN / NPM</label>
                        <input type="text" name="identifier" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>
                <div class="mt-3 text-center small text-muted">
                    <strong>Dosen:</strong> NIDN 123456 / pass: password123<br>
                    <strong>Mahasiswa:</strong> NPM 23067000101 / pass: passmhs1
                </div>
            </div>
        </div>
    </div>
</div>
@endsection