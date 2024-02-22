<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // Ambil semua data user
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user], 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['user' => $user, 'message' => 'User berhasil dibuat'], 201);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
        ]);

        // Cari user dan perbarui informasi
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => isset($request->password) ? bcrypt($request->password) : $user->password,
        ]);

        return response()->json(['user' => $user, 'message' => 'User berhasil diupdate'], 200);
    }

    public function destroy($id)
    {
        // Cari dan hapus user
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus'], 200);
    }
}
