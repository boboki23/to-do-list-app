<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\PasswordResetOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    public function showForm()
    {
        if (!session('otp_verified') || !session('reset_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Silahkan verifikasi OTP terlebih dahulu.');
        }

        return view('auth.reset-password');
    }

    public function reset(ResetPasswordRequest $request)
    {
        try {
            $data = $request->validated();
            $email = $data['email'];

            $sessionEmail = session('reset_email');
            if (!$sessionEmail || $sessionEmail !== $email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi tidak valid. Silahkan ulangi proses.',
                ], 400);
            }

            $otpRecord = PasswordResetOtp::where('email', $email)
                ->where('is_used', true)
                ->latest()
                ->first();

            if (!$otpRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP belum diverifikasi. Silahkan ulangi proses.',
                ], 400);
            }

            $user = DB::table('users')->where('email', $email)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.',
                ], 404);
            }

            DB::table('users')
                ->where('email', $email)
                ->update([
                    'password' => Hash::make($data['password']),
                    'updated_at' => now(),
                ]);

            PasswordResetOtp::where('email', $email)->delete();

            session()->forget(['reset_email', 'otp_verified']);

            Log::info("Password reset successful for {$email}");

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset. Silahkan login.',
            ]);

        } catch (\Exception $e) {
            Log::error('Password reset failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset password. Silahkan coba lagi.',
            ], 500);
        }
    }
}