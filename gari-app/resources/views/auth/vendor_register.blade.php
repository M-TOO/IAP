@extends('layouts.app') 
@section('title', 'Vendor Registration')

@section('content')
<style>
        .hero-bg {
            /* Change the URL to your specific image for vendor registration */
            background-image: url('{{ asset('images/vendor.jpg') }}') !important;
        }
        /* Optional: Change the location description textarea height for better visibility */
        #location_description, #shop_description {
            height: 100px; 
            resize: vertical;
        }
    </style>
    <h3 style="color: var(--color-vendor); font-size: 1.8rem; margin-bottom: 20px; text-align: center;">Vendor Shop Account Creation</h3>
    
    <form method="POST" action="{{ route('vendor.store') }}">
        @csrf
        
        <div class="form-group">
            <label for="owner_name">Owner's Full Name</label>
            <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required>
            @error('owner_name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="shop_description">Shop Description (Tell customers what you specialize in)</label>
            <textarea id="shop_description" name="shop_description" rows="3" required>{{ old('shop_description') }}</textarea>
            @error('shop_description') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-group">
            <label for="national_id">National ID Number</label>
            <input type="text" id="national_id" name="national_id" value="{{ old('national_id') }}" required>
            @error('national_id') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
            @error('phone_number') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="location_description">Location Description</label>
            <textarea id="location_description" name="location_description" rows="3" required>{{ old('location_description') }}</textarea>
            @error('location_description') <span class="error">{{ $message }}</span> @enderror
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

        <button type="submit" class="btn btn-vendor" style="margin-top: 20px;">Submit for Approval</button>
    </form>

    <p class="small-text" style="text-align: center; margin-top: 20px;">
        Already registered? 
        <a href="{{ route('vendor.login') }}" style="color: var(--color-vendor);">Sign In</a>
    </p>

    <div style="text-align: center; margin-top: 15px;">
        <a href="{{ url('/') }}" style="text-decoration: none; color: #666;">
            &larr; Back to Home
        </a>
    </div>
@endsection