<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    // Menampilkan halaman form ganti password
    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    // Memproses pergantian password
    public function change(Request $request)
    {
        // Validasi input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Cek apakah password lama sesuai dengan yang ada di database
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama yang Anda masukkan salah.',
            ]);
        }

        // Jika benar, update password baru
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Beri pesan sukses
        return back()->with('status', 'Password berhasil diganti!');
    }
}