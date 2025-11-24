@extends('layouts.app')
@section('title', 'Application Received')

@section('content')
<style>
    .hero-bg {
        /* Use the vendor background image */
        background-image: url('{{ asset('images/vendor.jpg') }}') !important;
    }
    .status-icon {
        font-size: 4rem;
        color: var(--color-vendor);
        margin-bottom: 20px;
    }
</style>

<div style="text-align: center;">
    <div class="status-icon">
        &#10003; {{-- Checkmark Icon --}}
    </div>

    <h3 style="color: var(--color-vendor); font-size: 2rem; margin-bottom: 15px;">
        Application Received!
    </h3>

    <p style="color: #ddd; font-size: 1.1rem; line-height: 1.6; margin-bottom: 30px;">
        Thank you for registering your shop with <strong>GARI</strong>.<br>
        Your account is currently <strong>Pending Approval</strong>.
    </p>

    <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #444;">
        <p style="color: #aaa; margin: 0; font-size: 0.95rem;">
            Our administrators will review your details (National ID and Shop Location).<br>
            Once approved, you will be able to log in using your email and password.
        </p>
    </div>

    <a href="{{ url('/') }}" class="btn btn-vendor" style="max-width: 250px; margin: 0 auto;">
        Return to Home
    </a>
</div>
@endsection