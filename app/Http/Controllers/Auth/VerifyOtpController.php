<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\PasswordResetOtp;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerifyOtpController extends Controller
{
    public function showForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Silahkan masukkan email terlebih dahulu.');
        }

        return view('auth.verify-otp');
    }

    public function verify(VerifyOtpRequest $request)
    {
        $data = $request->validated();
        $email = $data['email'];
        $otp = $data['otp'];

        $otpRecord = PasswordResetOtp::where('email', $email)
            ->where('otp', $otp)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            $existing = PasswordResetOtp::where('email', $email)
                ->where('otp', $otp)
                ->first();

            if ($existing && $existing->isExpired()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP sudah kadaluarsa. Silahkan kirim ulang.',
                ], 400);
            }

            if ($existing && $existing->is_used) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP sudah digunakan.',
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak valid.',
            ], 400);
        }

        $otpRecord->increment('attempts');

        if ($otpRecord->isMaxAttempts()) {
            $otpRecord->update(['is_used' => true]);
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak percobaan. Silahkan kirim ulang OTP.',
            ], 400);
        }

        $otpRecord->update(['is_used' => true]);

        session(['otp_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'OTP berhasil diverifikasi. Silahkan buat password baru.',
        ]);
    }

    public function resend(Request $request)
    {
        $email = session('reset_email');

        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi tidak valid. Silahkan ulangi proses.',
            ], 400);
        }

        try {
            PasswordResetOtp::where('email', $email)->delete();

            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            PasswordResetOtp::create([
                'email' => $email,
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
                'is_used' => false,
                'attempts' => 0,
            ]);

            \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\OtpMail($otp, $email));

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP baru telah dikirim',
                'otp' => $otp,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim ulang OTP. Silahkan coba lagi.',
            ], 500);
        }
    }
}