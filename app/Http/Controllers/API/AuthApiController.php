<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CustomAuthService;
use Illuminate\Http\Request;

class AuthApiController extends Controller
{
    public function login(Request $request, CustomAuthService $authService)
    {
        $request->validate([
            'identifier' => 'required',
            'password' => 'required',
        ]);

        $user = $authService->attemptLogin($request->identifier, $request->password);

        if (!$user) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id_user,
                'role' => $user->role,
                'nidn' => $user->nidn,
                'npm' => $user->npm,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}