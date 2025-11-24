@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('customer-dashboard-css')
<link rel="stylesheet" href="{{ asset('css/cdashboard.css') }}">
@endsection

@section('content')

<style>
    /* --- EXISTING STYLES --- */
    .part-image { overflow: hidden; position: relative; background: #0f0f1a; }
    .part-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
    .part-card:hover .part-image img { transform: scale(1.05); }

    /* --- MODAL STYLES --- */
    .modal-overlay {
        display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%;
        overflow: auto; background-color: rgba(0, 0, 0, 0.85); backdrop-filter: blur(5px); animation: fadeIn 0.3s;
    }
    .modal-content {
        background-color: #1a1a2e; margin: 5% auto; border: 1px solid #444; width: 90%; max-width: 900px;
        border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.7); display: flex; overflow: hidden; animation: slideUp 0.3s;
    }
    .modal-left { flex: 1; background: #000; display: flex; align-items: center; justify-content: center; min-height: 400px; }
    .modal-left img { width: 100%; height: 100%; object-fit: contain; max-height: 500px; }
    .modal-right { flex: 1; padding: 40px; display: flex; flex-direction: column; position: relative; }

    /* Typography */
    .modal-brand-badge {
        display: inline-block; background: var(--color-accent, #3498db); color: white; padding: 5px 12px;
        border-radius: 20px; font-size: 0.8rem; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; width: fit-content;
    }
    .modal-title { font-size: 2rem; color: white; margin: 0 0 10px 0; line-height: 1.2; }
    .modal-vendor { font-size: 1rem; color: #aaa; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
    .modal-price { font-size: 1.8rem; color: #4CAF50; font-weight: bold; margin-bottom: 25px; }
    .modal-description { color: #ddd; line-height: 1.6; font-size: 1rem; flex-grow: 1; margin-bottom: 30px; border-top: 1px solid #333; padding-top: 20px; }

    /* Contact Info Box (Hidden by default) */
    .contact-info-box {
        display: none; /* Hidden initially */
        background: rgba(52, 152, 219, 0.1);
        border: 1px solid #3498db;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        animation: fadeIn 0.4s;
    }
    .contact-label { color: #aaa; font-size: 0.9rem; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 1px; }
    .contact-value { color: white; font-size: 1.2rem; font-weight: bold; margin-bottom: 15px; display: block; text-decoration: none; }
    .contact-value:last-child { margin-bottom: 0; }
    .contact-value:hover { color: #3498db; text-decoration: underline; }

    .close-btn { position: absolute; top: 15px; right: 20px; color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; z-index: 10; }
    .close-btn:hover { color: white; }
    .modal-placeholder-icon { font-size: 5rem; }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    @media (max-width: 768px) { .modal-content { flex-direction: column; margin: 10% auto; width: 95%; } .modal-left { min-height: 250px; } .modal-right { padding: 20px; } }
</style>

<div class="dashboard-layout">

    <aside class="sidebar">
        <div class="sidebar-header"><div class="logo">GARI 🚗</div></div>
        <nav class="sidebar-nav">
            <a href="{{ route('customer.dashboard') }}" class="nav-item {{ Request::routeIs('customer.dashboard') ? 'active' : '' }}"><span class="nav-icon">📊</span> Dashboard</a>
            <a href="{{ route('customer.profile') }}" class="nav-item {{ Request::routeIs('customer.profile') ? 'active' : '' }}"><span class="nav-icon">👤</span> Profile</a>
        </nav>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit" class="logout-btn"><span class="nav-icon">🚪</span> Logout</button></form>
        </div>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <div class="search-container">
                <form action="{{ route('customer.dashboard') }}" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search by part name..." value="{{ request('search') }}" class="search-input">
                    <select name="brand" class="brand-filter">
                        <option value="">All Brands</option>
                        @foreach(['Ford', 'Toyota', 'Nissan', 'Honda', 'Mazda', 'Mercedes', 'BMW', 'Audi'] as $brand)
                            <option value="{{ $brand }}" @selected(request('brand') == $brand)>{{ $brand }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="search-btn">Search</button>
                </form>
            </div>
        </header>

        <div class="dashboard-content">
            <div class="results-header">
                <h2 class="results-title">Spare Parts Marketplace</h2>
                <p class="results-subtitle">
                    @if(request('search') || request('brand')) Results for: <strong>{{ request('search') ?? 'All' }}</strong> @if(request('brand')) ({{ request('brand') }}) @endif
                    @else Showing all available parts @endif
                </p>
            </div>

            <div class="search-results-container">
                @if(isset($parts) && $parts->count() > 0)
                    <div class="results-grid">
                        @foreach($parts as $part)
                        <div class="part-card">
                            <div class="part-image">
                                @if($part->image) <img src="{{ asset('storage/' . $part->image) }}" alt="{{ $part->name }}">
                                @else <div class="image-placeholder">🔧</div> @endif
                            </div>
                            <div class="part-details">
                                <h4 class="part-name">{{ $part->name }}</h4>
                                <div class="part-meta">
                                    <span class="part-brand">{{ $part->car_brand }}</span>
                                    {{-- 🟢 FIXED: Using owner_name since shop_name might not exist --}}
                                    <span class="part-vendor">🏪 {{ $part->vendor->owner_name ?? 'Vendor' }}</span>
                                </div>
                                <div class="part-price-row">
                                    <span class="part-price">KES {{ number_format($part->price, 0) }}</span>
                                    {{-- 🟢 PASSING VENDOR DETAILS --}}
                                    <button class="order-btn" 
                                        onclick='openPartModal(
                                            @json($part), 
                                            "{{ $part->vendor->owner_name ?? "Unknown Vendor" }}", 
                                            "{{ $part->vendor->phone_number ?? "N/A" }}",
                                            "{{ $part->vendor->email ?? "N/A" }}",
                                            "{{ $part->image ? asset("storage/" . $part->image) : "" }}"
                                        )'>
                                        View
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div style="margin-top: 30px;">{{ $parts->links() }}</div>
                @else
                    <div class="no-results"><div class="no-results-icon">🔍</div><h3>No parts found</h3><p>Try adjusting your filters or searching again.</p></div>
                @endif
            </div>
        </div>
    </main>
</div>

{{-- 🟢 PRODUCT DETAILS MODAL --}}
<div id="productModal" class="modal-overlay">
    <div class="modal-content">
        <span class="close-btn" onclick="closePartModal()">&times;</span>

        {{-- Left: Image --}}
        <div class="modal-left">
            <img id="modalImage" src="" alt="Part Image" style="display: none;">
            <div id="modalPlaceholder" class="image-placeholder modal-placeholder-icon" style="display: none;">🔧</div>
        </div>

        {{-- Right: Details --}}
        <div class="modal-right">
            <span id="modalBrand" class="modal-brand-badge">Brand</span>
            <h2 id="modalTitle" class="modal-title">Part Name</h2>
            <div class="modal-vendor" id="modalVendor">🏪 Vendor Name</div>
            <div class="modal-price" id="modalPrice">KES 0</div>

            <div class="modal-description">
                <p id="modalDesc">Description goes here...</p>
            </div>

            {{-- 🟢 CONTACT ACTION --}}
            <button id="contactBtn" class="order-btn" style="width: 100%; padding: 15px; font-size: 1.1rem;">
                Contact Vendor
            </button>

            {{-- 🟢 HIDDEN DETAILS (Revealed on Click) --}}
            <div id="contactInfoArea" class="contact-info-box">
                <div class="contact-label">Call Vendor</div>
                <a href="#" id="phoneLink" class="contact-value">
                    📞 <span id="vendorPhone"></span>
                </a>
                
                <div class="contact-label">Email Vendor</div>
                <a href="#" id="emailLink" class="contact-value">
                    ✉️ <span id="vendorEmail"></span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function openPartModal(part, vendorName, vendorPhone, vendorEmail, imageUrl) {
        // 1. Populate Data
        document.getElementById('modalTitle').innerText = part.name;
        document.getElementById('modalBrand').innerText = part.car_brand;
        document.getElementById('modalVendor').innerHTML = '🏪 ' + vendorName;
        document.getElementById('modalPrice').innerText = 'KES ' + new Intl.NumberFormat().format(part.price);
        document.getElementById('modalDesc').innerText = part.description;

        // 2. Populate Contact Info
        document.getElementById('vendorPhone').innerText = vendorPhone;
        document.getElementById('phoneLink').href = 'tel:' + vendorPhone;
        
        document.getElementById('vendorEmail').innerText = vendorEmail;
        document.getElementById('emailLink').href = 'mailto:' + vendorEmail;

        // 3. Reset Contact Button State
        document.getElementById('contactBtn').style.display = 'block';
        document.getElementById('contactInfoArea').style.display = 'none';

        // 4. Handle Image
        const imgEl = document.getElementById('modalImage');
        const placeholderEl = document.getElementById('modalPlaceholder');
        if (imageUrl) {
            imgEl.src = imageUrl;
            imgEl.style.display = 'block';
            placeholderEl.style.display = 'none';
        } else {
            imgEl.style.display = 'none';
            placeholderEl.style.display = 'flex';
        }

        // 5. Show Modal
        document.getElementById('productModal').style.display = 'block';
        document.body.style.overflow = 'hidden'; 
    }

    function closePartModal() {
        document.getElementById('productModal').style.display = 'none';
        document.body.style.overflow = 'auto'; 
    }

    // 🟢 Reveal Contact Info Logic
    document.getElementById('contactBtn').onclick = function() {
        this.style.display = 'none'; // Hide button
        document.getElementById('contactInfoArea').style.display = 'block'; // Show details
    };

    window.onclick = function(event) {
        const modal = document.getElementById('productModal');
        if (event.target == modal) closePartModal();
    }
</script>

@endsection