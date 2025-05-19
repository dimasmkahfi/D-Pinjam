<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class SimpleAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiToken = $request->header('api-token');

        if (!$apiToken) {
            return response()->json([
                'message' => 'API token tidak ada'
            ], 401);
        }

        // Mencari user berdasarkan token (dalam kasus ini, kita gunakan username sebagai token)
        $user = User::where('username', $apiToken)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Token tidak valid'
            ], 401);
        }

        // Simpan user di request untuk digunakan di controller
        $request->user = $user;

        return $next($request);
    }
}
