@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="register-card">
        <h1 class="register-title">Sign in to Your Account</h1>

        <form action="{{ route('login') }}" method="POST" class="register-form">
            @csrf

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-input"
                       placeholder="Enter your email"
                       required
                       autofocus>
                @error('email')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password"
                       name="password"
                       class="form-input"
                       placeholder="Enter your password"
                       required>
                @error('password')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin: 15px 0;">

            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    <span>Log In</span>
                </button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
            </div>

            <div style="margin-top: 20px; text-align: center;">
            </div>

            <div class="register-footer">
                <p class="footer-text">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="login-link">Sign Up</a>
                </p>
            </div>
        </form>
    </div>
@endsection
