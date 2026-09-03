@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="login-container">
    <div class="login-card">
        
        <!-- Bagian kiri: branding -->
        <div class="login-brand">
            <div class="brand-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2>Buat Akun</h2>
            <p class="brand-sub">To Do List App</p>
            <div class="brand-illustration">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <!-- Bagian kanan: form register -->
        <div class="login-form">
            <h3>Register</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Nama</label>
                    <input type="text" id="name" name="name" placeholder="Enter Name" value="{{ old('name') }}" required autofocus>
                </div>

                <!--  Email -->
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter Email" value="{{ old('email') }}" required>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required>
                        <span class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation"><i class="fas fa-check-circle"></i> Konfirmasi Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required>
                        <span class="toggle-password" id="togglePasswordConfirm">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>

                <p class="register-link">
                    Already have an account? <a href="{{ route('login') }}">Sign in now</a>
                </p>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const input = document.getElementById('password');
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    document.getElementById('togglePasswordConfirm')?.addEventListener('click', function() {
        const input = document.getElementById('password_confirmation');
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>
@endpush