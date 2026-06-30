<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Learning')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }
        
        .wrapper {
            display: flex;
            width: 100%;
            margin-top: 56px;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            width: 280px;
            position: fixed;
            left: 0;
            top: 56px;
            bottom: 0;
            overflow-y: auto;
            z-index: 100;
        }
        
        .content-wrapper {
            margin-left: 280px;
            flex: 1;
            padding: 20px;
        }
        
        .list-group-item {
            border: none;
            padding: 12px 20px;
            color: #495057;
            transition: all 0.3s;
        }
        
        .list-group-item:hover {
            background-color: #e7f3ff;
            color: #0d6efd;
        }
        
        .list-group-item.active {
            background-color: #0d6efd;
            color: #fff;
            border: none;
        }
        
        .list-group-item i {
            width: 25px;
        }
        
        .dropdown-menu {
            border-radius: 12px;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .dropdown-item.text-danger:hover {
            background-color: #fee;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                margin-top: 0;
            }
            
            .content-wrapper {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ Auth::check() ? (Auth::user()->dosen ? route('dosen.dashboard') : route('mahasiswa.dashboard')) : route('login') }}">
                <i class="fas fa-graduation-cap me-2"></i> E-Learning
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                @if(Auth::check())
                    @if(Auth::user()->dosen)
                    <!-- ===== DROPDOWN PROFILE DOSEN ===== -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownProfile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::user()->dosen->foto_profil)
                                    <img src="{{ asset('storage/' . Auth::user()->dosen->foto_profil) }}" 
                                         alt="Foto Profil" 
                                         class="rounded-circle me-2 border border-2 border-white" 
                                         width="40" 
                                         height="40"
                                         style="object-fit: cover;">
                                @else
                                    <div class="bg-white text-primary rounded-circle me-2 d-flex align-items-center justify-content-center fw-bold border border-2 border-white" 
                                         style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr(Auth::user()->dosen->nama_dosen, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="fw-semibold">{{ Auth::user()->dosen->nama_dosen }}</span>
                            </a>
                            
                            <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="navbarDropdownProfile" style="min-width: 320px;">
                                <li>
                                    <div class="card border-0">
                                        <div class="card-body p-4">
                                            <div class="text-center mb-3">
                                                @if(Auth::user()->dosen->foto_profil)
                                                    <img src="{{ asset('storage/' . Auth::user()->dosen->foto_profil) }}" 
                                                         alt="Foto Profil" 
                                                         class="rounded-circle border border-3 border-primary" 
                                                         width="80" 
                                                         height="80"
                                                         style="object-fit: cover;">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto fw-bold border border-3 border-primary" 
                                                         style="width: 80px; height: 80px; font-size: 32px;">
                                                        {{ strtoupper(substr(Auth::user()->dosen->nama_dosen, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <h6 class="text-center fw-bold mb-1">{{ Auth::user()->dosen->nama_dosen }}</h6>
                                            <p class="text-center text-muted small mb-3">NIDN: {{ Auth::user()->dosen->nidn }}</p>
                                            
                                            <hr class="my-2">
                                            
                                            <div class="mb-2">
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-envelope me-2 text-primary"></i><strong>Email:</strong>
                                                </small>
                                                <small class="ms-4">{{ Auth::user()->dosen->email ?? 'Belum diisi' }}</small>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-phone me-2 text-primary"></i><strong>No. HP:</strong>
                                                </small>
                                                <small class="ms-4">{{ Auth::user()->dosen->no_hp ?? 'Belum diisi' }}</small>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-building me-2 text-primary"></i><strong>Program Studi:</strong>
                                                </small>
                                                <small class="ms-4">{{ Auth::user()->dosen->prodi }}</small>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-book me-2 text-primary"></i><strong>Mata Kuliah:</strong>
                                                </small>
                                                <small class="ms-4">{{ \App\Models\Matakuliah::where('id_dosen', Auth::user()->dosen->id_dosen)->count() }} Mata Kuliah</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                
                                <li><hr class="dropdown-divider m-0"></li>
                                
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('dosen.profile') }}">
                                        <i class="fas fa-user-circle me-2 text-primary"></i> Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger w-100 text-start">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    @elseif(Auth::user()->mahasiswa)
                    <!-- ===== DROPDOWN PROFILE MAHASISWA ===== -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownProfileMhs" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::user()->mahasiswa->foto_profil)
                                    <img src="{{ asset('storage/' . Auth::user()->mahasiswa->foto_profil) }}" 
                                         alt="Foto Profil" 
                                         class="rounded-circle me-2 border border-2 border-white" 
                                         width="40" 
                                         height="40"
                                         style="object-fit: cover;">
                                @else
                                    <div class="bg-white text-primary rounded-circle me-2 d-flex align-items-center justify-content-center fw-bold border border-2 border-white" 
                                         style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr(Auth::user()->mahasiswa->nama_mahasiswa, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="fw-semibold">{{ Auth::user()->mahasiswa->nama_mahasiswa }}</span>
                            </a>
                            
                            <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="navbarDropdownProfileMhs" style="min-width: 320px;">
                                <li>
                                    <div class="card border-0">
                                        <div class="card-body p-4">
                                            <div class="text-center mb-3">
                                                @if(Auth::user()->mahasiswa->foto_profil)
                                                    <img src="{{ asset('storage/' . Auth::user()->mahasiswa->foto_profil) }}" 
                                                         alt="Foto Profil" 
                                                         class="rounded-circle border border-3 border-primary" 
                                                         width="80" 
                                                         height="80"
                                                         style="object-fit: cover;">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto fw-bold border border-3 border-primary" 
                                                         style="width: 80px; height: 80px; font-size: 32px;">
                                                        {{ strtoupper(substr(Auth::user()->mahasiswa->nama_mahasiswa, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <h6 class="text-center fw-bold mb-1">{{ Auth::user()->mahasiswa->nama_mahasiswa }}</h6>
                                            <p class="text-center text-muted small mb-3">NPM: {{ Auth::user()->mahasiswa->id_user }}</p>
                                            
                                            <hr class="my-2">
                                            
                                            <div class="mb-2">
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-graduation-cap me-2 text-primary"></i><strong>Program Studi:</strong>
                                                </small>
                                                <small class="ms-4">{{ Auth::user()->mahasiswa->prodi }}</small>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-users me-2 text-primary"></i><strong>Kelas:</strong>
                                                </small>
                                                <small class="ms-4">{{ Auth::user()->mahasiswa->kelas }}</small>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-layer-group me-2 text-primary"></i><strong>Semester:</strong>
                                                </small>
                                                <small class="ms-4">{{ Auth::user()->mahasiswa->semester }}</small>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-calendar me-2 text-primary"></i><strong>Angkatan:</strong>
                                                </small>
                                                <small class="ms-4">{{ Auth::user()->mahasiswa->angkatan ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                
                                <li><hr class="dropdown-divider m-0"></li>
                                
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('profile') }}">
                                        <i class="fas fa-user-circle me-2 text-primary"></i> Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger w-100 text-start">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    @endif
                @else
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </a>
                    </li>
                </ul>
                @endif
            </div>
        </div>
    </nav>
    
    <!-- Wrapper -->
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            @yield('sidebar')
        </div>
        
        <!-- Main Content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>