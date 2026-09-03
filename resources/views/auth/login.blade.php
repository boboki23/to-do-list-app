@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="login-container">
    <div class="login-card">
        
        <!-- Bagian kiri: branding -->
        <div class="login-brand">
            <div class="brand-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h2>Welcome</h2>
            <p class="brand-sub">To Do List App</p>
            <div class="brand-illustration">
                <i class="fas fa-laptop-code"></i>
            </div>
        </div>

        <!-- Bagian kanan: form login -->
        <div class="login-form">
            <h3>Login</h3>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('status') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Validation Errors -->
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

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter Email" value="{{ old('email') }}" required autofocus autocomplete="email">
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter Password" required autocomplete="current-password">
                        <span class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>

                <!-- Register Link -->
                <p class="register-link">
                    Don't have an account? <a href="{{ route('register') }}">Sign up now</a>
                </p>
                <p class="register-link">
                    Forgot your password? <a href="{{ route('password.request') }}">Reset it</a>
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
</script>
@endpush