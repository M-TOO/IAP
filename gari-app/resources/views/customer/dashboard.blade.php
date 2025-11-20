@extends('layouts.app')
@section('title', 'Customer Dashboard')

@section('content')
    <h3 style="color: var(--color-primary); font-size: 1.8rem; margin-bottom: 20px; text-align: center;">Customer Dashboard</h3>

    @if (session('success'))
        <div style="background-color: #38c172; color: #fff; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
            {{ session('success') }}
        </div>
    @endif
    
    <p style="color: #ddd; text-align: center;">Welcome! Your account is active and you are now logged in.</p>
    @endsection