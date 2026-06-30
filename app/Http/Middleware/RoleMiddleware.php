<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if (!$user) {
            return $request->is('api/*')
                ? response()->json(['message' => 'Unauthenticated'], 401)
                : redirect('/login');
        }

        // Debug: uncomment baris di bawah untuk melihat role user
        // dd('Role: ' . $user->role . ' | Expected: ' . $role);

        if ($user->role !== $role) {
            return $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized role'], 403)
                : abort(403, 'Akses ditolak. Role Anda: ' . $user->role);
        }

        return $next($request);
    }
}