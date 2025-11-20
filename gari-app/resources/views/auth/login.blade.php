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
    
    <form method="POST" action="{{ route('login.store') }}">
        @csrf
        
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

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