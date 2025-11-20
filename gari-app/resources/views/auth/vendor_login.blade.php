@extends('layouts.app') 
@section('title', 'Vendor Sign In')

@section('content')
    <style>
        .hero-bg {
            /* This overrides the default layout image with the Vendor-specific background */
            background-image: url('{{ asset('images/vendor.jpg') }}') !important;
        }
    </style>
    
    <h3 style="color: var(--color-vendor); font-size: 1.8rem; margin-bottom: 20px; text-align: center;">Vendor Shop Sign In</h3>
    <p class="small-text" style="text-align: center; margin-bottom: 15px;">
        Use your registered email and password to access your shop dashboard.
    </p>
    
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

        <button type="submit" class="btn btn-vendor" style="margin-top: 20px;">Sign In to Shop</button>
    </form>

    <p class="small-text" style="text-align: center; margin-top: 20px;">
        Not registered yet? 
        <a href="{{ route('vendor.register') }}" style="color: var(--color-vendor);">Create Shop Account</a>
    </p>
@endsection