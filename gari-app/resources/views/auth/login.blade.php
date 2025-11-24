@extends('layouts.app') 
@section('title', 'Sign In')

@section('content')
    <style>
        .hero-bg {
            /* Change the URL to your specific customer image */
            background-image: url('{{ asset('images/customer.jpg') }}') !important;
        }
    </style>
    
    <h3 style="color: var(--color-primary); font-size: 1.8rem; margin-bottom: 20px; text-align: center;">Sign In to GARI</h3>
    
    <!-- File: resources/views/auth/login.blade.php -->

<form method="POST" action="{{ route('login.store') }}">
    @csrf
    
    <!-- 🔑 FIXED: Change input name from 'name' to 'email' -->
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email') <span class="error">{{ $message }}</span> @enderror
    </div>

    <!-- Password Field -->
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        @error('password') <span class="error">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Sign In</button>
</form>

    <p class="small-text" style="text-align: center; margin-top: 20px;">
        Don't have an account? 
        <a href="{{ route('customer.register') }}" style="color: var(--color-primary);">Create Customer Account</a>
    </p>
@endsection