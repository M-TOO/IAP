@extends('layouts.app')
@section('title', 'Admin Dashboard - Management')

@section('content')
    <h3 style="color: var(--color-admin); font-size: 1.8rem; margin-bottom: 20px; text-align: center;">Administrator Management Dashboard</h3>
    
    <p style="color: #ddd; text-align: center;">(Admin tools and vendor approval logic will be implemented here.)</p>
    
    <div style="background-color: #333; color: #fff; padding: 15px; border-radius: 5px; margin-top: 30px; text-align: center;">
        Welcome, {{ Auth::user()->name }}.
    </div>
@endsection