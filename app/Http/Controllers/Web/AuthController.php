<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || $request->password != $user->password) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }

        // Gunakan username sebagai token
        $token = $user->username;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user);
    }

    public function updateFace(Request $request)
    {
        $request->validate([
            'face_id' => 'required',
            'face_image_path' => 'required',
            'face_encoding' => 'required'
        ]);

        $user = $request->user;
        $user->face_id = $request->face_id;
        $user->face_image_path = $request->face_image_path;
        $user->face_encoding = $request->face_encoding;
        $user->face_registered_at = now();
        $user->verification_status = 1;
        $user->save();

        return response()->json(['message' => 'Data wajah berhasil diperbarui', 'user' => $user]);
    }
}
