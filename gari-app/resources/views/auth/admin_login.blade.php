@extends('layouts.app') 
@section('title', 'Admin Sign In')

@section('content')
    <style>
        .hero-bg {
            /* Use a dark, system-focused image for admin */
            background-image: url('{{ asset('images/admin.jpg') }}'); 
        }
    </style>
    
    <h3 style="color: var(--color-admin); font-size: 1.8rem; margin-bottom: 20px; text-align: center;">Administrator Login</h3>
    
    <form method="POST" action="{{ route('login.store') }}">
        @csrf
        
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            @error('password') <span class="error">The password field is required.</span> @enderror
        </div>

        <button type="submit" class="btn btn-admin" style="margin-top: 20px;">Sign In as Admin</button>
    </form>

    <p class="small-text" style="text-align: center; margin-top: 20px;">
        This portal is for system administrators only.
    </p>
@endsection