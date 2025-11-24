@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<style>
    /* --- RESET & LAYOUT --- */
    .content-box-small {
        max-width: 100% !important; width: 100% !important; margin: 0 !important; padding: 0 !important;
        background: transparent !important; border: none !important; box-shadow: none !important;
    }
    .admin-layout { display: flex; min-height: 100vh; font-family: 'Segoe UI', sans-serif; }
    .hero-bg { background-image: url('{{ asset('images/admin.jpg') }}') !important; background-attachment: fixed; background-size: cover; }

    /* --- SIDEBAR --- */
    .admin-sidebar {
        width: 260px; background: rgba(15, 23, 42, 0.95); border-right: 1px solid rgba(255, 255, 255, 0.1);
        display: flex; flex-direction: column; padding: 20px 15px; position: fixed; top: 0; bottom: 0; left: 0; z-index: 100;
        backdrop-filter: blur(10px); transition: width 0.3s ease; overflow: hidden;
    }
    .admin-sidebar.collapsed { width: 70px; padding: 20px 10px; }

    /* Sidebar Header */
    .sidebar-header {
        display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 40px; padding-left: 5px; height: 50px;
    }
    .brand-container {
        display: flex; flex-direction: column; transition: opacity 0.2s, width 0.2s;
        overflow: hidden; white-space: nowrap;
    }
    .admin-sidebar.collapsed .brand-container { opacity: 0; width: 0; pointer-events: none; }
    
    .logo-text { color: white; margin: 0; font-size: 1.4rem; font-weight: bold; line-height: 1.2; }
    .logo-subtitle { color: #666; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }

    .toggle-btn {
        background: transparent; border: none; color: #aaa; cursor: pointer; font-size: 1.2rem; padding: 5px;
        transition: color 0.3s; display: flex; align-items: center; justify-content: center; height: 30px;
    }
    .toggle-btn:hover { color: white; }

    /* Navigation Links */
    .nav-link {
        display: flex; align-items: center; padding: 12px 15px; margin-bottom: 5px; color: #aaa;
        text-decoration: none; border-radius: 8px; transition: all 0.2s ease; font-size: 0.95rem; white-space: nowrap;
    }
    .nav-link:hover { background: rgba(255, 255, 255, 0.1); color: #fff; transform: translateX(5px); }
    .nav-link.active { background: var(--color-admin); color: #000; font-weight: bold; }
    
    .nav-icon { width: 24px; height: 24px; min-width: 24px; margin-right: 15px; fill: currentColor; transition: margin 0.3s; }
    .admin-sidebar.collapsed .nav-icon { margin-right: 0; }
    .nav-label { opacity: 1; transition: opacity 0.2s; }
    .admin-sidebar.collapsed .nav-label { opacity: 0; pointer-events: none; width: 0; }

    /* Sidebar Footer */
    .sidebar-footer { margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.05); }
    .admin-sidebar.collapsed .sidebar-footer { border-top: none; }

    /* --- MAIN CONTENT --- */
    .admin-main { flex: 1; margin-left: 260px; padding: 40px; transition: margin-left 0.3s ease; }
    .admin-main.collapsed { margin-left: 70px; }

    /* Stats Grid */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-top: 20px; margin-bottom: 40px; }
    .stat-card {
        background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 30px; border-radius: 12px; backdrop-filter: blur(5px); text-align: center;
        transition: transform 0.3s;
    }
    .stat-card:hover { transform: translateY(-5px); border-color: rgba(255,255,255,0.3); }
    .stat-number { font-size: 3rem; font-weight: 800; color: #fff; margin: 10px 0; text-shadow: 0 2px 10px rgba(0,0,0,0.5); }
    .stat-label { color: #aaa; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; }

</style>

<div class="admin-layout">
    
    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="brand-container">
                <h3 class="logo-text">GARI Admin</h3>
                <span class="logo-subtitle">System Control</span>
            </div>
            <button onclick="toggleSidebar()" class="toggle-btn" title="Toggle Sidebar">
                &#9776;
            </button>
        </div>

        <div style="flex: 1;">
            <a href="{{ route('admin.dashboard') }}" class="nav-link active" title="Dashboard">
                <svg class="nav-icon" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                <span class="nav-label">Dashboard</span>
            </a>
            
            <a href="{{ route('admin.vendors') }}" class="nav-link" title="Manage Vendors">
                <svg class="nav-icon" viewBox="0 0 24 24"><path d="M20 4H4v2h16V4zm1 10v-2l-1-5H4l-1 5v2h1v6h10v-6h4v6h2v-6h1zm-9 6H6v-6h6v6z"/></svg>
                <span class="nav-label">Manage Vendors</span>
            </a>
            
            <a href="{{ route('admin.users') }}" class="nav-link" title="Manage Users">
                <svg class="nav-icon" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                <span class="nav-label">Manage Users</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="width: 100%; cursor: pointer; background: transparent; border: none; color: #ff6b6b;" title="Logout">
                    <svg class="nav-icon" viewBox="0 0 24 24"><path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5a2 2 0 00-2 2v4h2V5h14v14H5v-4H3v4a2 2 0 002 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                    <span class="nav-label">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="admin-main" id="mainContent">
        <h2 style="color: white; font-size: 2rem; margin-bottom: 10px; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Dashboard Overview</h2>
        <p style="color: #ccc; margin-bottom: 40px;">Welcome back, {{ Auth::user()->name }}</p>

        {{-- 1. STATISTICS ROW --}}
        <div class="stats-grid">
            <div class="stat-card" style="border-bottom: 4px solid var(--color-vendor);">
                <div class="stat-number">{{ \App\Models\Vendor::where('status', 'pending_approval')->count() }}</div>
                <div class="stat-label">Pending Vendors</div>
            </div>
            <div class="stat-card" style="border-bottom: 4px solid var(--color-accent);">
                <div class="stat-number">{{ \App\Models\User::where('role', 'customer')->count() }}</div>
                <div class="stat-label">Total Customers</div>
            </div>
            <div class="stat-card" style="border-bottom: 4px solid var(--color-primary);">
                <div class="stat-number">{{ \App\Models\Vendor::where('status', 'approved')->count() }}</div>
                <div class="stat-label">Active Shops</div>
            </div>
        </div>

        {{-- 2. INFO BOX (Restored) --}}
        <div style="margin-top: 40px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 40px; text-align: center; color: #888;">
            <p style="font-size: 1.2rem; margin-bottom: 10px; color: #ddd;">Ready to manage the platform?</p>
            <p style="font-size: 1rem;">Select <strong style="color: var(--color-vendor);">Manage Vendors</strong> or <strong style="color: var(--color-accent);">Manage Users</strong> from the sidebar to begin.</p>
        </div>

    </main>

    {{-- Retractable Logic --}}
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            main.classList.toggle('collapsed');
            
            localStorage.setItem('sidebarState', sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const state = localStorage.getItem('sidebarState');
            if (state === 'collapsed') {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('mainContent').classList.add('collapsed');
            }
        });
    </script>
</div>
@endsection