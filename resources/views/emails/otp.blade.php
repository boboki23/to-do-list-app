<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Password</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: #f6f9fc;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 520px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h2 {
            color: #0b1e33;
            font-size: 22px;
            margin: 0;
        }
        .otp-box {
            background: #f0f7fe;
            padding: 20px;
            text-align: center;
            font-size: 36px;
            letter-spacing: 10px;
            font-weight: 700;
            border-radius: 12px;
            color: #0a66c2;
            margin: 25px 0;
            font-family: 'Courier New', monospace;
        }
        .info {
            color: #4a5c6e;
            font-size: 15px;
            line-height: 1.6;
        }
        .badge {
            display: inline-block;
            background: #eef6ff;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 13px;
            color: #0a66c2;
            margin: 4px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e6f0f5;
            font-size: 13px;
            color: #8aa0b5;
            text-align: center;
        }
        .warning {
            color: #b02a37;
            font-size: 13px;
            background: #fce8e6;
            padding: 10px 16px;
            border-radius: 8px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🔐 Reset Password</h2>
            <p style="color: #4a5c6e; margin-top: 5px;">Verifikasi identitas Anda</p>
        </div>

        <p style="font-size: 16px; color: #1e2f40;">
            Halo, <strong>{{ $email }}</strong>
        </p>

        <p class="info">
            Kami menerima permintaan untuk mereset password akun Anda.
            Gunakan kode OTP di bawah ini untuk melanjutkan:
        </p>

        <div class="otp-box">
            {{ $otp }}
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <span class="badge">⏱ Berlaku {{ $expiresIn }} menit</span>
            <span class="badge">🔒 Jaga kerahasiaan kode</span>
        </div>

        <div class="warning">
            ⚠️ Jika Anda tidak meminta reset password, abaikan email ini.
        </div>

        <p class="info" style="font-size: 14px;">
            Kode ini hanya berlaku <strong>satu kali</strong> dan akan kadaluarsa dalam 
            <strong>{{ $expiresIn }} menit</strong>.
        </p>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            <br>
            <small>Email ini dikirim secara otomatis, harap tidak membalas.</small>
        </div>
    </div>
</body>
</html>