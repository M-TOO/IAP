@extends('layouts.app') 
@section('title', 'Customer Registration')

@section('content')
<style>
        .hero-bg {
            background-image: url('{{ asset('images/customer.jpg') }}') !important;
        }
    </style>
    <h3 style="color: var(--color-primary); font-size: 1.8rem; margin-bottom: 20px; text-align: center;">Customer Account Creation</h3>
    
    <form method="POST" action="{{ route('customer.store') }}">
        @csrf
        
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            @error('password') <span class="error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Register Account</button>
    </form>

    <p class="small-text" style="text-align: center; margin-top: 20px;">
    Already have an account? 
    <a href="{{ route('login') }}" style="color: var(--color-primary);">Sign In</a> 
    </p>

    <div style="text-align: center; margin-top: 15px;">
        <a href="{{ url('/') }}" style="text-decoration: none; color: #666;">
            &larr; Back to Home
        </a>
    </div>
@endsection