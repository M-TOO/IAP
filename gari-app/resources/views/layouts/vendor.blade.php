<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vendor Dashboard')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* --- GLOBAL RESET --- */
        * {
            box-sizing: border-box;
        }

        :root {
            /* Theme Colors */
            --bg-dark: #0B0F19;
            --sidebar-bg: rgba(17, 24, 39, 0.95);
            --border-color: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: #94a3b8;
            --primary: #818cf8;
            --danger: #f87171;
            --success: #34d399;
            --warning: #fbbf24;
            --input-bg: #1a1f2e;
            --card-bg: rgba(20, 25, 40, 0.7);

            /* Layout Dimensions */
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
        }

        /* --- BASE --- */
        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            margin: 0; padding: 0;
            min-height: 100vh;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        /* --- SIDEBAR (Fixed) --- */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            padding: 20px 15px;
            backdrop-filter: blur(10px);
            transition: width 0.3s ease;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
            padding: 20px 10px;
        }

        /* Sidebar Header */
        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 50px;
            margin-bottom: 40px;
            padding-left: 5px;
        }

        .brand-container {
            display: flex; align-items: center; gap: 10px;
            font-size: 1.25rem; font-weight: 700; color: var(--text-main);
            transition: opacity 0.2s;
            overflow: hidden;
        }
        
        .sidebar.collapsed .brand-container span {
            opacity: 0; pointer-events: none; display: none;
        }

        .toggle-btn {
            background: transparent;
            border: none;
            color: #aaa;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 5px;
            width: 30px; height: 30px;
            display: flex; align-items: center; justify-content: center;
            transition: color 0.3s;
        }
        .toggle-btn:hover { color: white; }

        /* Navigation Links */
        .nav-links { flex: 1; display: flex; flex-direction: column; gap: 5px; }

        .nav-item {
            display: flex; align-items: center;
            padding: 12px 15px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }
        
        .nav-item:hover, .nav-item.active {
            background: rgba(129, 140, 248, 0.1);
            color: white;
        }
        
        .nav-icon {
            width: 24px; min-width: 24px;
            text-align: center; font-size: 1.1rem;
            margin-right: 15px;
            transition: margin 0.3s;
        }
        
        .nav-text { transition: opacity 0.2s; }

        .sidebar.collapsed .nav-icon { margin-right: 0; }
        .sidebar.collapsed .nav-text { opacity: 0; display: none; }
        .sidebar.collapsed .nav-item { justify-content: center; padding-left: 0; padding-right: 0; }

        /* Sidebar Footer */
        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid var(--border-color);
            padding-top: 20px;
        }
        .sidebar.collapsed .sidebar-footer { border-top: none; }

        .btn-logout {
            width: 100%;
            display: flex; align-items: center;
            background: rgba(248, 113, 113, 0.1);
            color: var(--danger);
            border: none;
            padding: 12px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-logout:hover { background: var(--danger); color: white; }
        .sidebar.collapsed .btn-logout { justify-content: center; background: transparent; }
        .sidebar.collapsed .btn-logout span { display: none; }
        .sidebar.collapsed .btn-logout i { margin-right: 0; }

        /* --- MAIN CONTENT --- */
        .main-content {
            /* The Fix: Subtract sidebar width from 100% */
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            
            padding: 2rem;
            background: radial-gradient(circle at top right, rgba(129, 140, 248, 0.08), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(52, 211, 153, 0.05), transparent 40%);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        .main-content.collapsed {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* --- UTILITIES --- */
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
            margin-bottom: 2rem;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: var(--sidebar-width); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; width: 100%; }
            .toggle-btn { display: none; }
        }
    </style>
    
    @yield('page-css')
</head>
<body>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="brand-container">
                <i class="fas fa-cube text-primary" style="color: var(--primary);"></i> 
                <span>GARI Vendor</span>
            </div>
            
            <button class="toggle-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <nav class="nav-links">
            <a href="{{ route('vendor.dashboard') }}" class="nav-item {{ Request::routeIs('vendor.dashboard') ? 'active' : '' }}" title="Dashboard">
                <i class="fas fa-chart-pie nav-icon"></i> 
                <span class="nav-text">Dashboard</span>
            </a>
            
            <a href="{{ route('vendor.products.create') }}" class="nav-item {{ Request::routeIs('vendor.products.create') ? 'active' : '' }}" title="Add Product">
                <i class="fas fa-plus-circle nav-icon"></i> 
                <span class="nav-text">Add Product</span>
            </a>

            <a href="#" class="nav-item" style="opacity: 0.5; cursor: not-allowed;" title="Orders">
                <i class="fas fa-file-invoice-dollar nav-icon"></i> 
                <span class="nav-text">Orders (Soon)</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout" title="Logout">
                    <i class="fas fa-sign-out-alt nav-icon" style="margin-right: 15px;"></i> 
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content" id="mainContent">
        @if(session('success'))
            <div style="background: rgba(52, 211, 153, 0.1); border: 1px solid var(--success); color: var(--success); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
            
            // Save state
            localStorage.setItem('vendorSidebarState', sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const state = localStorage.getItem('vendorSidebarState');
            if (state === 'collapsed') {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('mainContent').classList.add('collapsed');
            }
        });
    </script>
</body>
</html>