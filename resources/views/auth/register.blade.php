@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="card" style="max-width: 400px; margin: 40px auto; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h1 style="margin: 0 0 25px 0; font-size: 24px; text-align: center; color: #333;">Register</h1>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="form-input"
                       style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; box-sizing: border-box;"
                       required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-input"
                       style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; box-sizing: border-box;"
                       required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Password</label>
                <input type="password"
                       name="password"
                       class="form-input"
                       style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; box-sizing: border-box;"
                       required
                       minlength="8">
                <small style="color: #6b7280; font-size: 14px; display: block; margin-top: 5px;">Minimum 8 characters</small>
            </div>
            <div class="form-group" style="margin-bottom: 25px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Confirm Password</label>
                <input type="password"
                       name="password_confirmation"
                       class="form-input"
                       style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; box-sizing: border-box;"
                       required>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 25px;">
                <button type="submit" class="btn btn-primary" style="flex: 1; padding: 12px; background-color: #3b82f6; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; font-weight: 500;">Register</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary" style="padding: 12px 20px; background-color: #6b7280; color: white; text-decoration: none; border-radius: 4px; font-size: 16px; text-align: center; font-weight: 500;">Cancel</a>
            </div>

            <div style="margin-top: 25px; padding-top: 20px; text-align: center; border-top: 1px solid #eee;">
                <p style="margin: 0; color: #666;">Already have an account? <a href="{{ route('login') }}" style="color: #3b82f6; text-decoration: none; font-weight: 500;">Login</a></p>
            </div>
        </form>
    </div>
@endsection
