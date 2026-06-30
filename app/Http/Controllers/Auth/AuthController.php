<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CustomAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request, CustomAuthService $authService)
    {
        $request->validate([
            'identifier' => 'required',
            'password' => 'required',
        ]);

        $user = $authService->attemptLogin($request->identifier, $request->password);

        if (!$user) {
            return back()->withErrors(['identifier' => 'NIDN/NPM atau password salah.']);
        }

        // Login manual menggunakan guard default
        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/' . $user->role . '/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}