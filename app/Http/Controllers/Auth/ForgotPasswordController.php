<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Mail\OtpMail;
use App\Models\PasswordResetOtp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(ForgotPasswordRequest $request)
    {
        try {
            $email = $request->validated()['email'];

            PasswordResetOtp::where('email', $email)
                ->where(function($query) {
                    $query->where('is_used', true)
                          ->orWhere('expires_at', '<', Carbon::now());
                })
                ->delete();

            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            PasswordResetOtp::create([
                'email' => $email,
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
                'is_used' => false,
                'attempts' => 0,
            ]);

            Mail::to($email)->send(new OtpMail($otp, $email));

            Log::info("OTP sent to {$email}: {$otp}");

            session(['reset_email' => $email]);

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP telah dikirim ke email Anda',
                'otp' => $otp,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send OTP: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP. Silahkan coba lagi.',
            ], 500);
        }
    }
}