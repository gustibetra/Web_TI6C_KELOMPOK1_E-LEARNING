<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomAuthService
{
    public function attemptLogin(string $identifier, string $password): ?User
    {
        // Cari berdasarkan NIDN (dosen) atau NPM (mahasiswa)
        $user = User::where('nidn', $identifier)
                    ->orWhere('npm', $identifier)
                    ->first();

        if (!$user) return null;

        // Password di database masih plain text, bandingkan langsung
        // Jika sudah di-hash, ganti dengan Hash::check($password, $user->password)
        if ($user->password !== $password) return null;

        return $user;
    }
}