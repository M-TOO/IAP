<div class="admin-layout">
    <style>
        /* --- RESET & LAYOUT --- */
        .content-box-small { max-width: 100% !important; width: 100% !important; margin: 0 !important; background: transparent !important; border: none !important; box-shadow: none !important; }
        .hero-bg { background-image: url('{{ asset('images/admin.jpg') }}') !important; background-attachment: fixed; background-size: cover; }
        .admin-layout { display: flex; min-height: 100vh; font-family: 'Segoe UI', sans-serif; }

        /* --- SIDEBAR --- */
        .admin-sidebar { width: 260px; background: rgba(15, 23, 42, 0.95); border-right: 1px solid rgba(255, 255, 255, 0.1); display: flex; flex-direction: column; padding: 20px 15px; position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; backdrop-filter: blur(10px); transition: width 0.3s ease; overflow: hidden; }
        .admin-sidebar.collapsed { width: 70px; padding: 20px 10px; }

        /* Sidebar Header */
        .sidebar-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 40px; padding-left: 5px; height: 50px; }
        .brand-container { display: flex; flex-direction: column; transition: opacity 0.2s, width 0.2s; overflow: hidden; white-space: nowrap; }
        .admin-sidebar.collapsed .brand-container { opacity: 0; width: 0; pointer-events: none; }
        .logo-text { color: white; margin: 0; font-size: 1.4rem; font-weight: bold; line-height: 1.2; }
        .logo-subtitle { color: #666; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
        .toggle-btn { background: transparent; border: none; color: #aaa; cursor: pointer; font-size: 1.2rem; padding: 5px; transition: color 0.3s; display: flex; align-items: center; justify-content: center; height: 30px; }
        .toggle-btn:hover { color: white; }

        /* Navigation Links */
        .nav-link { display: flex; align-items: center; padding: 12px 15px; margin-bottom: 5px; color: #aaa; text-decoration: none; border-radius: 8px; transition: all 0.2s ease; font-size: 0.95rem; white-space: nowrap; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.1); color: #fff; transform: translateX(5px); }
        .nav-link.active { background: var(--color-accent); color: #fff; font-weight: bold; } /* Blue for Users */
        .nav-icon { width: 24px; height: 24px; min-width: 24px; margin-right: 15px; fill: currentColor; transition: margin 0.3s; }
        .admin-sidebar.collapsed .nav-icon { margin-right: 0; }
        .nav-label { opacity: 1; transition: opacity 0.2s; }
        .admin-sidebar.collapsed .nav-label { opacity: 0; pointer-events: none; width: 0; }
        .sidebar-footer { margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.05); }
        .admin-sidebar.collapsed .sidebar-footer { border-top: none; }

        /* --- CONTENT --- */
        .admin-main { flex: 1; margin-left: 260px; padding: 40px; transition: margin-left 0.3s ease; }
        .admin-main.collapsed { margin-left: 70px; }

        /* TABLES & UI */
        .glass-panel { background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; backdrop-filter: blur(10px); padding: 25px; margin-bottom: 30px; }
        .admin-table { width: 100%; border-collapse: collapse; color: #ddd; }
        .admin-table th { text-align: left; padding: 15px; border-bottom: 2px solid rgba(255,255,255,0.1); color: #aaa; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; }
        .admin-table td { padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .admin-table tr:hover { background: rgba(255,255,255,0.05); }
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; }
        .badge-active { background: rgba(76, 175, 80, 0.2); color: #4CAF50; border: 1px solid #4CAF50; }
        .badge-suspended { background: rgba(255, 77, 77, 0.2); color: #ff4d4d; border: 1px solid #ff4d4d; }
        
        /* MODAL */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 200; display: flex; justify-content: center; align-items: center; }
        .modal-content { background: #1a1a2e; padding: 30px; border-radius: 12px; border: 1px solid #444; width: 90%; max-width: 400px; text-align: center; }
        select { width: 100%; padding: 10px; margin: 15px 0; background: #2a2a40; color: white; border: 1px solid #555; border-radius: 4px; }
    </style>

    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="brand-container">
                <h3 class="logo-text">GARI Admin</h3>
                <span class="logo-subtitle">System Control</span>
            </div>
            <button onclick="toggleSidebar()" class="toggle-btn" title="Toggle Sidebar">&#9776;</button>
        </div>
        <div style="flex: 1;">
            <a href="{{ route('admin.dashboard') }}" class="nav-link" title="Dashboard">
                <svg class="nav-icon" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg><span class="nav-label">Dashboard</span>
            </a>
            <a href="{{ route('admin.vendors') }}" class="nav-link" title="Manage Vendors">
                <svg class="nav-icon" viewBox="0 0 24 24"><path d="M20 4H4v2h16V4zm1 10v-2l-1-5H4l-1 5v2h1v6h10v-6h4v6h2v-6h1zm-9 6H6v-6h6v6z"/></svg><span class="nav-label">Manage Vendors</span>
            </a>
            <a href="{{ route('admin.users') }}" class="nav-link active" title="Manage Users">
                <svg class="nav-icon" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg><span class="nav-label">Manage Users</span>
            </a>
        </div>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="width: 100%; cursor: pointer; background: transparent; border: none; color: #ff6b6b;" title="Logout">
                    <svg class="nav-icon" viewBox="0 0 24 24"><path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5a2 2 0 00-2 2v4h2V5h14v14H5v-4H3v4a2 2 0 002 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg><span class="nav-label">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="admin-main" id="mainContent">
        <h2 style="color: white; font-size: 2rem; margin-bottom: 30px; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">User Moderation</h2>

        @if(session()->has('message')) <div class="glass-panel" style="background: rgba(255, 152, 0, 0.2); border-color: #ff9800; color: #ff9800; text-align: center;">{{ session('message') }}</div> @endif

        <div class="glass-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="color: var(--color-accent); margin: 0;">Registered Customers</h3>
                <input wire:model.live="search" type="text" placeholder="Search users..." style="background: rgba(0,0,0,0.3); border: 1px solid #444; padding: 8px 15px; border-radius: 6px; color: white; width: 250px;">
            </div>

            <table class="admin-table">
                <thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td style="font-weight: bold; color: white;">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->suspended_until && $user->suspended_until->isFuture()) <span class="badge badge-suspended">Suspended</span>
                            @else <span class="badge badge-active">Active</span> @endif
                        </td>
                        <td>
                            @if($user->suspended_until && $user->suspended_until->isFuture())
                                <button wire:click="unsuspendUser({{ $user->id }})" style="background: transparent; border: 1px solid #4CAF50; color: #4CAF50; padding: 4px 10px; border-radius: 4px; cursor: pointer;">Unsuspend</button>
                            @else
                                <button wire:click="confirmSuspension({{ $user->id }})" style="background: transparent; border: 1px solid #ff9800; color: #ff9800; padding: 4px 10px; border-radius: 4px; cursor: pointer;">Suspend</button>
                            @endif
                        </td>
                    </tr>
                    @empty <tr><td colspan="4" style="text-align: center; padding: 30px; color: #888;">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top: 20px;">{{ $users->links(data: ['scrollTo' => false]) }}</div>
        </div>
    </main>

    @if($confirmingSuspension)
        <div class="modal-overlay">
            <div class="modal-content">
                <h3 style="color: var(--color-accent);">Suspend User</h3>
                <p style="color: #ccc;">Select duration for <strong>{{ $userToSuspendName }}</strong></p>
                <select wire:model="suspensionDuration">
                    <option value="1">24 Hours</option>
                    <option value="3">3 Days</option>
                    <option value="7">1 Week</option>
                    <option value="30">1 Month</option>
                    <option value="-1">Indefinitely (Ban)</option>
                </select>
                <div style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
                    <button wire:click="suspendUser" style="background: #d32f2f; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">Confirm</button>
                    <button wire:click="cancelSuspension" style="background: #444; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">Cancel</button>
                </div>
            </div>
        </div>
    @endif
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');
            sidebar.classList.toggle('collapsed');
            main.classList.toggle('collapsed');
            localStorage.setItem('sidebarState', sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded');
        }
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('sidebarState') === 'collapsed') {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('mainContent').classList.add('collapsed');
            }
        });
    </script>
</div>