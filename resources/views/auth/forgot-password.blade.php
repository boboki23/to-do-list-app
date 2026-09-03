<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - To Do List App</title>
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
        .input-wrapper { 
            display: flex; 
            align-items: center; 
            background: #f2f7fb; 
            border-radius: 12px; 
            padding: 0 14px; 
            border: 2px solid #dde7ef; 
            transition: 0.2s; 
        }
        .input-wrapper:focus-within { 
            border-color: #0a66c2; 
            background: #ffffff; 
            box-shadow: 0 0 0 4px rgba(10,102,194,0.08); 
        }
        .input-wrapper i { 
            color: #5f7d9c; 
            font-size: 0.9rem; 
        }
        .input-wrapper input { 
            width: 100%; 
            padding: 12px 10px; 
            border: none; 
            background: transparent; 
            font-size: 0.95rem; 
            color: #0b1e33; 
            outline: none; 
        }
        .input-wrapper input::placeholder { color: #8aa0b5; }
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
        .links { 
            display: flex; 
            justify-content: space-between; 
            margin-top: 1.2rem; 
            font-size: 0.85rem; 
        }
        .links a { 
            color: #0a66c2; 
            text-decoration: none; 
            font-weight: 500; 
            transition: 0.2s; 
        }
        .links a:hover { 
            color: #0858a8; 
            text-decoration: underline; 
        }
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
        .demo-badge { 
            display: inline-block; 
            background: #e8f0fe; 
            padding: 4px 14px; 
            border-radius: 20px; 
            font-size: 11px; 
            color: #1f4465; 
            margin-top: 12px; 
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <h1>🔐 Lupa Password</h1>
            <p>Masukkan email terdaftar, kami kirim kode OTP</p>
        </div>

        @if(session('error'))
            <div class="alert-error"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        <form id="forgotForm" onsubmit="return handleSendOTP(event)">
            @csrf

            <div class="form-group">
                <label><i class="fas fa-envelope" style="margin-right: 4px;"></i> Alamat Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope-open-text"></i>
                    <input type="email" id="emailInput" placeholder="Enter Email" required autocomplete="email" />
                </div>
            </div>

            <button type="submit" class="btn-primary" id="sendOtpBtn">
                <i class="fas fa-paper-plane"></i> Kirim Kode OTP
            </button>

            <div id="message" style="margin-top: 12px;"></div>
            
            <div style="text-align: center;">
                <span class="demo-badge"><i class="fas fa-code"></i> OTP akan muncul di email</span>
            </div>
        </form>

        <div class="links-center">
            <a href="{{ route('login') }}"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
        </div>
    </div>

    <script>
        async function handleSendOTP(event) {
            event.preventDefault();

            const email = document.getElementById('emailInput').value.trim();
            const messageDiv = document.getElementById('message');
            const btn = document.getElementById('sendOtpBtn');

            if (!email) {
                messageDiv.innerHTML = `<div class="alert-error"><i class="fas fa-circle-exclamation"></i> Email wajib diisi</div>`;
                return false;
            }

            btn.disabled = true;
            btn.innerHTML = `<i class="fas fa-spinner spinner"></i> Mengirim...`;

            try {
                const response = await fetch('{{ route("password.send.otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ email })
                });

                const data = await response.json();

                if (data.success) {
                    messageDiv.innerHTML = `
                        <div class="alert-success">
                            <i class="fas fa-check-circle"></i> 
                            ${data.message}
                            <div style="font-size: 0.8rem; margin-top: 6px; background: #e8f0fe; padding: 4px 12px; border-radius: 6px; display: inline-block;">
                                <i class="fas fa-info-circle"></i> Kode OTP: <strong>${data.otp}</strong>
                            </div>
                        </div>
                    `;

                    setTimeout(() => {
                        window.location.href = '{{ route("password.verify") }}';
                    }, 1500);

                } else {
                    messageDiv.innerHTML = `<div class="alert-error"><i class="fas fa-times-circle"></i> ${data.message}</div>`;
                    btn.disabled = false;
                    btn.innerHTML = `<i class="fas fa-paper-plane"></i> Kirim Kode OTP`;
                }

            } catch (error) {
                console.error('Error:', error);
                messageDiv.innerHTML = `<div class="alert-error"><i class="fas fa-times-circle"></i> Terjadi kesalahan. Silahkan coba lagi.</div>`;
                btn.disabled = false;
                btn.innerHTML = `<i class="fas fa-paper-plane"></i> Kirim Kode OTP`;
            }

            return false;
        }
    </script>
</body>
</html>