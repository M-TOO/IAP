@extends('layouts.app')
@section('title', 'My Profile')

{{-- Inject our specific CSS file --}}
@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/customer_profile.css') }}">
@endsection

@section('content')

    <div class="profile-header">
        <h3 class="dashboard-heading" style="margin:0;">My Profile</h3>
        <a href="{{ route('customer.dashboard') }}" class="btn-back">Back to Dashboard</a>
    </div>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if ($errors->any())
        <div style="background-color: rgba(255, 77, 77, 0.1); border: 1px solid #ff4d4d; padding: 15px; border-radius: 6px; margin-bottom: 25px; color: #ff4d4d;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 1. Update Info Form -->
    <div style="margin-bottom: 40px; border-bottom: 1px solid #444; padding-bottom: 40px;">
        <h4 class="section-title">Account Information</h4>
        <form action="{{ route('customer.profile.update') }}" method="POST" class="profile-form-grid">
            @csrf
            @method('PATCH')
    
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                <!-- Individual error messages are handled by the global error block above for simplicity, or inline below -->
            </div>
    
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
    
            <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Update Info</button>
        </form>
    </div>

    <!-- 2. Change Password Form (NEW) -->
    <div style="margin-bottom: 40px;">
        <h4 class="section-title">Change Password</h4>
        <form action="{{ route('customer.profile.password') }}" method="POST" class="profile-form-grid">
            @csrf
            @method('PUT')
    
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" required>
            </div>
    
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" name="password_confirmation" required>
            </div>
    
            <button type="submit" class="btn btn-primary" style="margin-top: 10px; background-color: var(--color-vendor); border-color: var(--color-vendor);">Update Password</button>
        </form>
    </div>

    <!-- 3. Delete Account Section -->
    <div class="danger-zone">
        <div class="danger-title">Delete Account</div>
        <p class="danger-text">
            Deleting your account is permanent. All your data and order history will be wiped immediately.
        </p>

        <form action="{{ route('customer.profile.destroy') }}" method="POST" onsubmit="return confirm('Are you sure? This cannot be undone.');">
            @csrf
            @method('DELETE')
            
            <div class="form-group">
                <label style="color: #ff4d4d; font-size: 0.9em;">Confirm Password to Delete</label>
                <input type="password" name="password" placeholder="Enter password to confirm" required 
                       style="border-color: #ff4d4d; background-color: rgba(255, 77, 77, 0.1);">
            </div>

            <button type="submit" class="btn-delete">Delete Account</button>
        </form>
    </div>

@endsection