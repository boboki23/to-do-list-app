<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - To Do List App</title>
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
        .input-wrapper .toggle-eye {
            cursor: pointer;
            color: #8aa0b5;
            padding: 4px;
            transition: 0.2s;
        }
        .input-wrapper .toggle-eye:hover { color: #0a66c2; }
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
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
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
        .password-requirements {
            font-size: 0.75rem;
            color: #4e6b85;
            margin-top: 6px;
            display: flex;
            flex-wrap: wrap;
            gap: 4px 16px;
        }
        .password-requirements span {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .req-met { color: #0f7b3e; }
        .req-met i { color: #0f7b3e; }
        .req-unmet { color: #b02a37; }
        .req-unmet i { color: #b02a37; }
        .match-success { color: #0f7b3e; font-size: 0.8rem; margin-top: 4px; }
        .match-error { color: #b02a37; font-size: 0.8rem; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="card">
        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step-dot done"></div>
            <div class="step-dot done"></div>
            <div class="step-dot active"></div>
        </div>

        <div class="logo">
            <h1>🔒 Buat Password Baru</h1>
            <p>Masukkan password baru untuk akun Anda</p>
        </div>

        @if(session('error'))
            <div class="alert-error"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>
        @endif

        <form id="resetForm" onsubmit="return handleResetPassword(event)">
            <input type="hidden" id="emailHidden" value="{{ session('reset_email') }}">

            <div class="form-group">
                <label><i class="fas fa-lock" style="margin-right: 4px;"></i> Password Baru</label>
                <div class="input-wrapper">
                    <i class="fas fa-key"></i>
                    <input type="password" id="newPassword" placeholder="Minimal 8 karakter" required />
                    <i class="fas fa-eye toggle-eye" id="togglePass1" onclick="togglePassword('newPassword','togglePass1')"></i>
                </div>
                <div class="password-requirements">
                    <span id="reqLength" class="req-unmet"><i class="fas fa-circle"></i> Min 8 karakter</span>
                    <span id="reqUpper" class="req-unmet"><i class="fas fa-circle"></i> Huruf besar</span>
                    <span id="reqNumber" class="req-unmet"><i class="fas fa-circle"></i> Angka</span>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-check-circle" style="margin-right: 4px;"></i> Konfirmasi Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-check"></i>
                    <input type="password" id="confirmPassword" placeholder="Ulangi password baru" required />
                    <i class="fas fa-eye toggle-eye" id="togglePass2" onclick="togglePassword('confirmPassword','togglePass2')"></i>
                </div>
                <div id="matchMessage" style="margin-top: 4px;"></div>
            </div>

            <button type="submit" class="btn-primary btn-success" id="resetBtn">
                <i class="fas fa-save"></i> Reset Password
            </button>

            <div id="resetMessage" style="margin-top: 12px;"></div>
        </form>

        <div class="links-center">
            <a href="{{ route('login') }}"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
        </div>
    </div>

    <script>
        const resetEmail = document.getElementById('emailHidden').value;

        if (!resetEmail) {
            window.location.href = '{{ route("password.request") }}';
        }

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('newPassword').addEventListener('input', function() {
            const pass = this.value;
            const reqLength = document.getElementById('reqLength');
            const reqUpper = document.getElementById('reqUpper');
            const reqNumber = document.getElementById('reqNumber');

            if (pass.length >= 8) {
                reqLength.className = 'req-met';
                reqLength.innerHTML = '<i class="fas fa-check-circle"></i> Min 8 karakter';
            } else {
                reqLength.className = 'req-unmet';
                reqLength.innerHTML = '<i class="fas fa-circle"></i> Min 8 karakter';
            }

            if (/[A-Z]/.test(pass)) {
                reqUpper.className = 'req-met';
                reqUpper.innerHTML = '<i class="fas fa-check-circle"></i> Huruf besar';
            } else {
                reqUpper.className = 'req-unmet';
                reqUpper.innerHTML = '<i class="fas fa-circle"></i> Huruf besar';
            }

            if (/\d/.test(pass)) {
                reqNumber.className = 'req-met';
                reqNumber.innerHTML = '<i class="fas fa-check-circle"></i> Angka';
            } else {
                reqNumber.className = 'req-unmet';
                reqNumber.innerHTML = '<i class="fas fa-circle"></i> Angka';
            }

            checkPasswordMatch();
        });

        document.getElementById('confirmPassword').addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const pass = document.getElementById('newPassword').value;
            const confirm = document.getElementById('confirmPassword').value;
            const msg = document.getElementById('matchMessage');

            if (confirm.length === 0) {
                msg.innerHTML = '';
                return;
            }

            if (pass === confirm) {
                msg.innerHTML = '<span class="match-success"><i class="fas fa-check-circle"></i> Password cocok</span>';
            } else {
                msg.innerHTML = '<span class="match-error"><i class="fas fa-times-circle"></i> Password tidak sama</span>';
            }
        }

        async function handleResetPassword(event) {
            event.preventDefault();

            const password = document.getElementById('newPassword').value;
            const confirm = document.getElementById('confirmPassword').value;
            const email = resetEmail;
            const msgDiv = document.getElementById('resetMessage');
            const btn = document.getElementById('resetBtn');

            const hasLength = password.length >= 8;
            const hasUpper = /[A-Z]/.test(password);
            const hasNumber = /\d/.test(password);

            if (!hasLength || !hasUpper || !hasNumber) {
                msgDiv.innerHTML = `<div class="alert-error"><i class="fas fa-circle-exclamation"></i> Password harus memenuhi semua syarat</div>`;
                return false;
            }

            if (password !== confirm) {
                msgDiv.innerHTML = `<div class="alert-error"><i class="fas fa-circle-exclamation"></i> Konfirmasi password tidak sama</div>`;
                return false;
            }

            btn.disabled = true;
            btn.innerHTML = `<i class="fas fa-spinner spinner"></i> Memproses...`;

            try {
                const response = await fetch('{{ route("password.reset") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        password_confirmation: confirm
                    })
                });

                const data = await response.json();

                if (data.success) {
                    msgDiv.innerHTML = `
                        <div class="alert-success">
                            <i class="fas fa-check-circle"></i> 
                            <div>
                                <strong>${data.message}</strong>
                                <div style="font-size: 0.8rem; margin-top: 4px;">Anda akan dialihkan ke halaman login...</div>
                            </div>
                        </div>
                    `;
                    btn.innerHTML = `<i class="fas fa-check"></i> Selesai`;

                    setTimeout(() => {
                        window.location.href = '{{ route("login") }}';
                    }, 2000);

                } else {
                    msgDiv.innerHTML = `<div class="alert-error"><i class="fas fa-times-circle"></i> ${data.message}</div>`;
                    btn.disabled = false;
                    btn.innerHTML = `<i class="fas fa-save"></i> Reset Password`;
                }

            } catch (error) {
                console.error('Error:', error);
                msgDiv.innerHTML = `<div class="alert-error"><i class="fas fa-times-circle"></i> Terjadi kesalahan. Silahkan coba lagi.</div>`;
                btn.disabled = false;
                btn.innerHTML = `<i class="fas fa-save"></i> Reset Password`;
            }

            return false;
        }
    </script>
</body>
</html>