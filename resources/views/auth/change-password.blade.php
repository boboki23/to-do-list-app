<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: #fff;
            width: 900px;
            max-width: 100%;
            display: flex;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 20px;
            overflow: hidden;
            margin: 20px;
        }

        /* Left Panel (Dark) */
        .left-panel {
            background-color: #1e293b; /* Navy Dark */
            color: #fff;
            width: 40%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .left-panel .icon-large {
            font-size: 50px;
            margin-bottom: 20px;
            opacity: 0.8;
        }

        .left-panel h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .left-panel p {
            font-size: 14px;
            opacity: 0.8;
            line-height: 1.6;
        }

        .left-panel .bottom-icon {
            font-size: 80px;
            margin-top: 40px;
            opacity: 0.3;
        }

        /* Right Panel (Form) */
        .right-panel {
            width: 60%;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right-panel h3 {
            color: #1e293b;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .input-group .icon {
            position: absolute;
            left: 15px;
            top: 48px;
            color: #1e293b;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
            background-color: #f8fafc;
        }

        .input-group input:focus {
            border-color: #3b82f6;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background-color: #1e293b;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-submit:hover {
            background-color: #334155;
        }

        .footer-link {
            text-align: center;
            margin-top: 20px;
        }

        .footer-link a {
            color: #3b82f6;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }

        /* Alert Messages */
        .alert {
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-danger {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .error-list { margin-left: 15px; }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .container { flex-direction: column; }
            .left-panel { width: 100%; padding: 30px; }
            .right-panel { width: 100%; padding: 30px; }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Left Panel (Dark) -->
        <div class="left-panel">
            <i class="fa-solid fa-shield-halved icon-large"></i>
            <h2>Secure Your Account</h2>
            <p>Change your password periodically to keep your data safe.</p>
            <i class="fa-solid fa-laptop-code bottom-icon"></i>
        </div>

        <!-- Right Panel (Form) -->
        <div class="right-panel">
            <h3>Change Password</h3>

            <!-- Show success message -->
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Show validation errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.change.post') }}">
                @csrf
                
                <div class="input-group">
                    <label for="current_password">Current Password</label>
                    <i class="fa-solid fa-lock icon"></i>
                    <input type="password" id="current_password" name="current_password" placeholder="Enter your current password" required>
                </div>

                <div class="input-group">
                    <label for="new_password">New Password</label>
                    <i class="fa-solid fa-lock icon"></i>
                    <input type="password" id="new_password" name="new_password" placeholder="Minimum 8 characters" required>
                </div>

                <div class="input-group">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <i class="fa-solid fa-lock icon"></i>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Repeat new password" required>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-key"></i> Change Password
                </button>
            </form>

            <div class="footer-link">
                <a href="{{ route('dashboard') }}">Back to Dashboard</a>
            </div>
        </div>
    </div>

</body>
</html>