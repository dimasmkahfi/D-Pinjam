<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:6',
            'lvl_users' => 'required|string|in:admin,manajer_area,master_admin,pdi,kepala_bengkel,satpam',
        ]);

        User::create([
            'username' => $request->username,
            'password' => $request->password, // Catatan: di aplikasi nyata gunakan Hash::make()
            'lvl_users' => $request->lvl_users,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users')->ignore($user->user_id, 'user_id'),
            ],
            'lvl_users' => 'required|string|in:admin,manajer_area,master_admin,pdi,kepala_bengkel,satpam',
        ]);

        $data = [
            'username' => $request->username,
            'lvl_users' => $request->lvl_users,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password; // Catatan: di aplikasi nyata gunakan Hash::make()
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
