<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - To Do List App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Roboto, sans-serif; }
        body { 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: linear-gradient(145deg, #f6f9fc 0%, #e6f0f5 100%); 
            padding: 1.5rem; 
        }
        .card { 
            max-width: 420px; 
            width: 100%; 
            background: #ffffff; 
            border-radius: 32px; 
            padding: 2.5rem 2rem; 
            box-shadow: 0 20px 40px -12px rgba(0,20,30,0.25); 
        }
        .logo { 
            text-align: center; 
            margin-bottom: 2rem; 
        }
        .logo h1 { 
            font-size: 1.8rem; 
            color: #0b1e33; 
        }
        .logo p { 
            color: #4a5c6e; 
            font-size: 0.9rem; 
        }
        .form-group { margin-bottom: 1.2rem; }
        label { 
            display: block; 
            font-weight: 500; 
            font-size: 0.85rem; 
            color: #1e2f40; 
            margin-bottom: 4px; 
        }
        .otp-input-group { 
            display: flex; 
            gap: 10px; 
            justify-content: center; 
            margin: 10px 0 6px; 
        }
        .otp-input-group input { 
            width: 52px; 
            height: 58px; 
            text-align: center; 
            font-size: 1.5rem; 
            font-weight: 600; 
            border: 2px solid #dde7ef; 
            border-radius: 12px; 
            background: #f2f7fb; 
            transition: 0.2s; 
        }
        .otp-input-group input:focus { 
            border-color: #0a66c2; 
            background: #ffffff; 
            box-shadow: 0 0 0 4px rgba(10,102,194,0.08); 
            outline: none; 
        }
        .timer { 
            text-align: center; 
            font-size: 0.85rem; 
            color: #4a5c6e; 
            margin-top: 6px; 
        }
        .timer span { 
            font-weight: 600; 
            color: #0a66c2; 
        }
        .timer .expired { color: #b02a37; }
        .btn-primary { 
            width: 100%; 
            background: #0a66c2; 
            border: none; 
            padding: 14px; 
            border-radius: 40px; 
            font-weight: 600; 
            font-size: 1rem; 
            color: white; 
            cursor: pointer; 
            transition: 0.2s; 
            box-shadow: 0 8px 18px -6px rgba(10,102,194,0.3); 
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-primary:hover { background: #0858a8; transform: translateY(-1px); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
        .btn-outline { 
            background: transparent; 
            border: 2px solid #dde7ef; 
            color: #1e2f40; 
            box-shadow: none; 
            margin-top: 8px;
        }
        .btn-outline:hover { background: #f2f7fb; transform: none; }
        .links-center {
            text-align: center;
            margin-top: 1.2rem;
            font-size: 0.85rem;
        }
        .links-center a { 
            color: #0a66c2; 
            text-decoration: none; 
            font-weight: 500; 
            transition: 0.2s; 
        }
        .links-center a:hover { 
            color: #0858a8; 
            text-decoration: underline; 
        }
        .alert-error { 
            background: #fce8e6; 
            color: #b02a37; 
            padding: 10px 14px; 
            border-radius: 10px; 
            font-size: 0.9rem; 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            margin-bottom: 1rem; 
        }
        .alert-success { 
            background: #d9f0e1; 
            color: #0f5132; 
            padding: 10px 14px; 
            border-radius: 10px; 
            font-size: 0.9rem; 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            margin-bottom: 1rem; 
        }
        .spinner { animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .step-indicator { 
            display: flex; 
            justify-content: center; 
            gap: 12px; 
            margin-bottom: 1.5rem; 
        }
        .step-dot { 
            width: 40px; 
            height: 6px; 
            border-radius: 10px; 
            background: #dde7ef; 
            transition: 0.3s; 
        }
        .step-dot.active { background: #0a66c2; width: 50px; }
        .step-dot.done { background: #0a66c2; }
    </style>
</head>
<body>
    <div class="card">
        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step-dot done"></div>
            <div class="step-dot active"></div>
            <div class="step-dot"></div>
        </div>

        <div class="logo">
            <h1>🔑 Verifikasi OTP</h1>
            <p>Masukkan 6 digit kode yang dikirim ke email Anda</p>
        </div>

        @if(session('error'))
            <div class="alert-error"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>
        @endif

        <div id="stepOtp">
            <div class="form-group">
                <label><i class="fas fa-otp" style="margin-right: 4px;"></i> Kode OTP</label>
                <div class="otp-input-group">
                    <input type="text" maxlength="1" class="otp-input" data-index="0" />
                    <input type="text" maxlength="1" class="otp-input" data-index="1" />
                    <input type="text" maxlength="1" class="otp-input" data-index="2" />
                    <input type="text" maxlength="1" class="otp-input" data-index="3" />
                    <input type="text" maxlength="1" class="otp-input" data-index="4" />
                    <input type="text" maxlength="1" class="otp-input" data-index="5" />
                </div>
                <div class="timer">
                    <i class="fas fa-clock"></i> Kode berlaku <span id="timerDisplay">05:00</span>
                </div>
            </div>

            <button class="btn-primary" id="verifyOtpBtn" onclick="verifyOTP()">
                <i class="fas fa-check-circle"></i> Verifikasi OTP
            </button>

            <button class="btn-primary btn-outline" onclick="resendOTP()">
                <i class="fas fa-rotate-right"></i> Kirim Ulang OTP
            </button>

            <div id="otpMessage" style="margin-top: 12px;"></div>
        </div>

        <div class="links-center">
            <a href="{{ route('login') }}"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
        </div>
    </div>

    <script>
        const resetEmail = '{{ session('reset_email') }}';

        if (!resetEmail) {
            window.location.href = '{{ route("password.request") }}';
        }

        // OTP Input - Auto Focus
        document.querySelectorAll('.otp-input').forEach((input, idx, arr) => {
            input.addEventListener('input', function(e) {
                this.value = this.value.replace(/\D/g, '');
                if (this.value.length === 1 && idx < arr.length - 1) {
                    arr[idx + 1].focus();
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && idx > 0) {
                    arr[idx - 1].focus();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const digits = paste.replace(/\D/g, '').slice(0, 6);
                digits.split('').forEach((d, i) => {
                    if (arr[i]) arr[i].value = d;
                });
                const nextIdx = Math.min(digits.length, 5);
                if (arr[nextIdx]) arr[nextIdx].focus();
            });
        });

        // Timer
        let secondsLeft = 300;
        let timerInterval;

        function startTimer() {
            clearInterval(timerInterval);
            secondsLeft = 300;
            updateTimerDisplay();
            timerInterval = setInterval(() => {
                secondsLeft--;
                updateTimerDisplay();
                if (secondsLeft <= 0) {
                    clearInterval(timerInterval);
                    document.getElementById('timerDisplay').classList.add('expired');
                    document.getElementById('otpMessage').innerHTML = `
                        <div class="alert-error">
                            <i class="fas fa-clock"></i> Kode OTP telah kadaluarsa. Kirim ulang.
                        </div>
                    `;
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const mins = String(Math.floor(secondsLeft / 60)).padStart(2, '0');
            const secs = String(secondsLeft % 60).padStart(2, '0');
            document.getElementById('timerDisplay').textContent = `${mins}:${secs}`;
            if (secondsLeft <= 0) {
                document.getElementById('timerDisplay').textContent = '⏰ Expired';
            }
        }

        function getOTPFromInput() {
            let otp = '';
            document.querySelectorAll('.otp-input').forEach(inp => {
                otp += inp.value;
            });
            return otp;
        }

        async function verifyOTP() {
            const otp = getOTPFromInput();
            const msgDiv = document.getElementById('otpMessage');
            const btn = document.getElementById('verifyOtpBtn');

            if (otp.length < 6) {
                msgDiv.innerHTML = `<div class="alert-error"><i class="fas fa-circle-exclamation"></i> Masukkan 6 digit kode OTP</div>`;
                return;
            }

            btn.disabled = true;
            btn.innerHTML = `<i class="fas fa-spinner spinner"></i> Memverifikasi...`;

            try {
                const response = await fetch('{{ route("password.verify.otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ email: resetEmail, otp: otp })
                });

                const data = await response.json();

                if (data.success) {
                    msgDiv.innerHTML = `<div class="alert-success"><i class="fas fa-check-circle"></i> ${data.message}</div>`;
                    setTimeout(() => {
                        window.location.href = '{{ route("password.reset.form") }}';
                    }, 1500);
                } else {
                    msgDiv.innerHTML = `<div class="alert-error"><i class="fas fa-times-circle"></i> ${data.message}</div>`;
                    btn.disabled = false;
                    btn.innerHTML = `<i class="fas fa-check-circle"></i> Verifikasi OTP`;
                    document.querySelectorAll('.otp-input').forEach(inp => inp.value = '');
                    document.querySelector('.otp-input').focus();
                }
            } catch (error) {
                console.error('Error:', error);
                msgDiv.innerHTML = `<div class="alert-error"><i class="fas fa-times-circle"></i> Terjadi kesalahan. Silahkan coba lagi.</div>`;
                btn.disabled = false;
                btn.innerHTML = `<i class="fas fa-check-circle"></i> Verifikasi OTP`;
            }
        }

        async function resendOTP() {
            const msgDiv = document.getElementById('otpMessage');
            const btn = document.querySelector('.btn-outline');

            btn.disabled = true;
            btn.innerHTML = `<i class="fas fa-spinner spinner"></i> Mengirim...`;

            try {
                const response = await fetch('{{ route("password.resend.otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ email: resetEmail })
                });

                const data = await response.json();

                if (data.success) {
                    msgDiv.innerHTML = `
                        <div class="alert-success">
                            <i class="fas fa-paper-plane"></i> ${data.message}
                            <div style="font-size: 0.8rem; margin-top: 6px; background: #e8f0fe; padding: 4px 12px; border-radius: 6px; display: inline-block;">
                                <i class="fas fa-info-circle"></i> Kode OTP baru: <strong>${data.otp}</strong>
                            </div>
                        </div>
                    `;
                    document.querySelectorAll('.otp-input').forEach(inp => inp.value = '');
                    document.querySelector('.otp-input').focus();
                    startTimer();
                } else {
                    msgDiv.innerHTML = `<div class="alert-error"><i class="fas fa-times-circle"></i> ${data.message}</div>`;
                }
            } catch (error) {
                console.error('Error:', error);
                msgDiv.innerHTML = `<div class="alert-error"><i class="fas fa-times-circle"></i> Gagal mengirim ulang OTP.</div>`;
            }

            btn.disabled = false;
            btn.innerHTML = `<i class="fas fa-rotate-right"></i> Kirim Ulang OTP`;
        }

        startTimer();
    </script>
</body>
</html>