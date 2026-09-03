<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Tampilkan halaman registrasi
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi user
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Redirect ke halaman login dengan pesan sukses
        // (TIDAK langsung login)
        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silahkan login dengan akun Anda.');
    }
}