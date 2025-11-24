@extends('layouts.app')

{{-- Override content class to allow full width --}}
@section('content-class', 'w-100 p-0')

@section('content')

{{-- 
    =========================================
    INTERNAL STYLES (UPDATED)
    =========================================
--}}
<style>
    :root {
        --bg-darkest: #0B0F19;
        --card-bg-dark: rgba(20, 25, 40, 0.85);
        --input-bg: #1a1f2e;
        --border-color: rgba(255, 255, 255, 0.1);
        --primary-light: #818cf8;
        --warning-light: #fbbf24;
        --success-light: #34d399;
        --danger-light: #f87171;
        --text-white: #ffffff;
        --text-light-muted: #e2e8f0;
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background-color: var(--bg-darkest);
        color: var(--text-white);
        margin: 0; padding: 0;
    }

    /* Page Background & Scrolling */
    .dashboard-wrapper {
        background-color: var(--bg-darkest);
        background-image: 
            radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
            radial-gradient(at 100% 100%, hsla(225,39%,25%,1) 0, transparent 50%);
        background-attachment: fixed;
        min-height: 100vh;
        width: 100%;
        padding-bottom: 100px;
    }

    .container-fluid {
        padding: 2rem;
    }

    /* ----- FLEXBOX LAYOUTS ----- */
    .header-flex, .card-body-flex { 
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .stats-container { 
        display: flex;
        flex-wrap: wrap; 
        gap: 1.5rem; 
        margin-bottom: 3rem;
    }
    .stat-item { 
        flex: 1 1 300px; 
    }

    /* ----- PRODUCT GRID (4 ITEMS) ----- */
    .product-row { 
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
    }
    .product-col { 
        /* 25% width means 4 items per row */
        flex: 0 0 calc(25% - 1.5rem); 
        min-width: 240px; 
    }
    
    .form-row { display: flex; flex-wrap: wrap; gap: 3rem; }
    .form-col { flex: 1 1 45%; min-width: 300px; }

    /* ----- COMPONENT STYLES ----- */

    .stat-card {
        background: var(--card-bg-dark);
        backdrop-filter: blur(12px);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        transition: transform 0.3s ease;
        min-height: 160px; 
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .stat-card:hover { transform: translateY(-5px); }

    .card-glow-primary { border-bottom: 3px solid var(--primary-light); }
    .card-glow-warning { border-bottom: 3px solid var(--warning-light); }
    .card-glow-success { border-bottom: 3px solid var(--success-light); }

    .icon-box {
        width: 60px; height: 60px; 
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255, 255, 255, 0.05);
        font-size: 1.2rem;
    }

    .modern-card {
        background: var(--card-bg-dark);
        backdrop-filter: blur(12px);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .product-card {
        background: rgba(30, 35, 50, 0.9);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .product-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary-light);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
    }

    .product-image-container {
        height: 200px;
        width: 100%;
        position: relative; background: #0f172a; overflow: hidden;
        display: flex; align-items: center; justify-content: center;
    }
    .placeholder-image {
        width: 100%; height: 100%; background: #0f172a; display: flex; align-items: center; justify-content: center;
        color: rgba(255, 255, 255, 0.3);
    }

    .price-tag {
        position: absolute; bottom: 10px; right: 10px;
        background: rgba(0, 0, 0, 0.8);
        color: var(--success-light); font-weight: bold;
        padding: 6px 14px; border-radius: 8px;
        border: 1px solid var(--success-light);
    }

    .brand-pill {
        font-size: 0.75rem; background: rgba(255, 255, 255, 0.1);
        color: var(--text-white); padding: 4px 12px;
        border-radius: 20px; white-space: nowrap;
    }

    .status-dot { height: 8px; width: 8px; border-radius: 50%; display: inline-block; }
    .bg-success-light { background-color: var(--success-light); }
    .bg-danger-light { background-color: var(--danger-light); }

    .btn-icon-danger {
        background: rgba(248, 113, 113, 0.1);
        border: 1px solid var(--danger-light);
        color: var(--danger-light);
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
    }

    /* Form Styles */
    .custom-label { color: var(--text-white); font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; display: block;}
    .custom-input, .custom-select {
        background-color: var(--input-bg);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: var(--text-white) !important; /* Forces text to be white */
        border-radius: 10px; padding: 0.75rem 1rem; width: 100%; box-sizing: border-box;
    }
    .custom-input:focus, .custom-select:focus {
        background-color: var(--input-bg);
        border-color: var(--primary-light);
        box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2);
        color: var(--text-white);
    }
    .custom-input-group-text {
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: var(--text-light-muted);
        border-top-left-radius: 10px; border-bottom-left-radius: 10px;
        padding: 0.75rem 1rem;
        display: flex; align-items: center;
    }
    .input-group { display: flex; }
    .upload-zone {
        border: 2px dashed rgba(255, 255, 255, 0.2);
        border-radius: 12px; background: rgba(0, 0, 0, 0.2);
        position: relative; padding: 2rem;
    }
    .file-input-hidden { position: absolute; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; cursor: pointer; }
    .btn-primary {
        background-color: var(--primary-light); border: none; color: white;
        padding: 1rem; border-radius: 10px; cursor: pointer; font-weight: bold; width: 100%;
    }
    
    /* Utilities */
    .text-light-muted { color: var(--text-light-muted) !important; }
    .text-primary-light { color: var(--primary-light) !important; }
    .text-warning-light { color: var(--warning-light) !important; }
    .text-success-light { color: var(--success-light) !important; }
    .text-danger-light { color: var(--danger-light) !important; }
    .border-white-10 { border-color: rgba(255, 255, 255, 0.1) !important; }
    .bg-darker-subtle { background-color: rgba(0, 0, 0, 0.2); }
    .bg-primary-soft { background-color: rgba(129, 140, 248, 0.1); }
    .bg-warning-soft { background-color: rgba(251, 191, 36, 0.1); }
    .bg-success-soft { background-color: rgba(52, 211, 153, 0.1); }
    
    .mb-5 { margin-bottom: 3rem; } .mb-4 { margin-bottom: 1.5rem; } .mb-2 { margin-bottom: 0.5rem; } .mb-0 { margin-bottom: 0; }
    .p-4 { padding: 1.5rem; } .p-md-5 { padding: 3rem; } .px-4 { padding-left: 1.5rem; padding-right: 1.5rem; } .py-4 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
    .h2 { font-size: 2rem; font-weight: bold; } .display-6 { font-size: 2.5rem; } .fw-bold { font-weight: bold; } .fs-4 { font-size: 1.5rem; } .fs-5 { font-size: 1.25rem; }
    .d-flex { display: flex; } .justify-content-between { justify-content: space-between; } .align-items-center { align-items: center; } .flex-column { flex-direction: column; } .h-100 { height: 100%; } .w-100 { width: 100%; }
    .fade-in-up { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; transform: translateY(20px); }
    @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

    /* 🟢 NEW: LOGOUT BUTTON STYLE */
    .btn-logout {
        background: rgba(248, 113, 113, 0.15);
        color: var(--danger-light);
        border: 1px solid rgba(248, 113, 113, 0.3);
        padding: 8px 24px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-logout:hover {
        background: var(--danger-light);
        color: white;
        box-shadow: 0 4px 15px rgba(248, 113, 113, 0.3);
        transform: translateY(-2px);
    }
</style>

{{-- 
    =========================================
    DASHBOARD CONTENT (LARAVEL LOGIC)
    =========================================
--}}
<div class="dashboard-wrapper">
    <div class="container-fluid">
        
        <div class="row mb-5 fade-in-up">
            <div class="header-flex"> 
                <div>
                    <h1 class="display-6 text-white fw-bold mb-2">
                        <i class="fas fa-tools me-2 text-primary-light"></i> Vendor Dashboard
                    </h1>
                    <p class="text-light-muted mb-0 fs-5">Manage inventory & track performance</p>
                </div>
                
                {{-- 🟢 NEW: DATE & LOGOUT CONTAINER --}}
                <div class="d-flex align-items-center" style="gap: 15px;">
                    
                    {{-- Date Badge --}}
                    <div style="background: rgba(255,255,255,0.05); padding: 8px 16px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.1);">
                        <i class="far fa-calendar-alt me-2"></i> {{ now()->format('M d, Y') }}
                    </div>

                    {{-- Logout Button --}}
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn-logout" title="Logout">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>

                </div>
            </div>
        </div>

        <div class="stats-container fade-in-up" style="animation-delay: 0.1s;">
            <div class="stat-item">
                <div class="stat-card h-100 card-glow-primary">
                    <div class="card-body p-4 card-body-flex"> 
                        <div>
                            <div class="text-uppercase text-light-muted fw-bold small tracking-wider mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">
                                <i class="fas fa-tags me-1"></i> Total Products
                            </div>
                            <div class="h2 text-white fw-bold mb-0">{{ $products->count() }}</div>
                        </div>
                        <div class="icon-box bg-primary-soft">
                            <i class="fas fa-tags text-primary-light"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-card h-100 card-glow-warning">
                    <div class="card-body p-4 card-body-flex">
                        <div>
                            <div class="text-uppercase text-light-muted fw-bold small tracking-wider mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">
                                <i class="fas fa-shopping-cart me-1"></i> New Orders
                            </div>
                            <div class="h2 text-white fw-bold mb-0">0</div>
                        </div>
                        <div class="icon-box bg-warning-soft">
                            <i class="fas fa-shopping-cart text-warning-light"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-card h-100 card-glow-success">
                    <div class="card-body p-4 card-body-flex">
                        <div>
                            <div class="text-uppercase text-light-muted fw-bold small tracking-wider mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">
                                <i class="fas fa-dollar-sign me-1"></i> Total Revenue
                            </div>
                            <div class="h2 text-white fw-bold mb-0">$0.00</div>
                        </div>
                        <div class="icon-box bg-success-soft">
                            <i class="fas fa-dollar-sign text-success-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5 fade-in-up" style="animation-delay: 0.2s;">
            <div class="modern-card">
                <div class="card-header border-0 bg-transparent py-4 px-4 header-flex">
                    <h5 class="text-white fw-bold mb-0 fs-4" style="font-size: 1.5rem;">
                        <i class="fas fa-boxes me-2 text-primary-light"></i> Product Catalog
                    </h5>
                    <span class="badge bg-dark-subtle border border-secondary text-white px-3 py-2 rounded-pill">
                        {{ $products->count() }} Items
                    </span>
                </div>
                
                <div class="card-body p-4">
                    @if($products->count() > 0)
                        <div class="product-row">
                            @foreach($products as $product)
                                <div class="product-col">
                                    <div class="product-card h-100">
                                        <div class="product-image-container">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width:100%; height:100%; object-fit:cover;">
                                            @else
                                                <div class="placeholder-image">
                                                    <i class="fas fa-image fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="price-tag">
                                                ${{ number_format($product->price, 2) }}
                                            </div>
                                        </div>

                                        <div class="p-3 d-flex flex-column h-100">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="text-white fw-bold mb-0 text-truncate" style="max-width: 70%;" title="{{ $product->name }}">{{ $product->name }}</h6>
                                                <span class="brand-pill">{{ $product->car_brand }}</span>
                                            </div>
                                            
                                            <p class="text-light-muted small mb-3 flex-grow-1" style="line-height: 1.5;">
                                                {{ Str::limit($product->description, 60) }}
                                            </p>

                                            <div class="d-flex justify-content-between align-items-center pt-3 border-top border-white-10">
                                                <div class="d-flex align-items-center">
                                                    <span class="status-dot {{ $product->is_available ? 'bg-success-light' : 'bg-danger-light' }} me-2"></span>
                                                    <span class="text-light-muted small">Stock: {{ $product->stock_quantity }}</span>
                                                </div>
                                                
                                                <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-icon-danger" onclick="return confirm('Delete this product?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state text-center py-5">
                            <h4 class="text-white fw-bold mb-2">Inventory is Empty</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row fade-in-up" style="animation-delay: 0.3s;">
            <div class="modern-card">
                <div class="card-header border-0 bg-transparent py-4 px-4">
                    <h5 class="text-white fw-bold mb-0 fs-4" style="font-size: 1.5rem;">
                        <i class="fas fa-plus-circle me-2 text-primary-light"></i> Add New Spare Part
                    </h5>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form id="addProductForm" action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-col">
                                <div class="mb-4">
                                    <label for="productName" class="custom-label">
                                        <i class="fas fa-tag me-2"></i>Part Name <span class="text-danger-light">*</span>
                                    </label>
                                    <input type="text" class="custom-input" id="productName" name="name" placeholder="e.g. Ceramic Brake Pads" required>
                                </div>

                                <div class="mb-4">
                                    <label for="productDescription" class="custom-label">
                                        <i class="fas fa-align-left me-2"></i>Description <span class="text-danger-light">*</span>
                                    </label>
                                    <textarea class="custom-input" id="productDescription" name="description" rows="5" placeholder="Detailed specifications..." required></textarea>
                                </div>

                                <div class="form-row" style="gap: 1.5rem;">
                                    <div style="flex: 1;">
                                        <label for="productPrice" class="custom-label">
                                            <i class="fas fa-dollar-sign me-2"></i>Price ($)
                                        </label>
                                        <div class="input-group">
                                            <span class="custom-input-group-text">$</span>
                                            <input type="number" class="custom-input" style="border-top-left-radius: 0; border-bottom-left-radius: 0;" id="productPrice" name="price" step="0.01" min="0" required>
                                        </div>
                                    </div>
                                    <div style="flex: 1;">
                                        <label for="stockQuantity" class="custom-label">
                                            <i class="fas fa-boxes me-2"></i>Stock Quantity
                                        </label>
                                        <input type="number" class="custom-input" id="stockQuantity" name="stock_quantity" min="0" value="0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-col">
                                <div class="mb-4">
                                    <label for="carBrand" class="custom-label">
                                        <i class="fas fa-car me-2"></i>Compatibility <span class="text-danger-light">*</span>
                                    </label>
                                    <select class="custom-select" id="carBrand" name="car_brand" required>
                                        <option value="" selected disabled>Select Brand</option>
                                        <option value="Ford">Ford</option>
                                        <option value="Toyota">Toyota</option>
                                        <option value="Nissan">Nissan</option>
                                        <option value="Mazda">Mazda</option>
                                        <option value="Honda">Honda</option>
                                        <option value="Mercedes">Mercedes</option>
                                        <option value="BMW">BMW</option>
                                        <option value="Audi">Audi</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="custom-label">
                                        <i class="fas fa-image me-2"></i>Product Image
                                    </label>
                                    <div class="upload-zone text-center">
                                        <input type="file" class="file-input-hidden" id="productImage" name="image" accept="image/*">
                                        <label for="productImage" style="cursor: pointer;">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-primary-light mb-3"></i>
                                            <h6 class="text-white fw-semibold mb-1">Tap to Upload</h6>
                                            <p class="text-light-muted small mb-0">Max 2MB (JPG, PNG)</p>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4 p-3 rounded bg-darker-subtle d-flex align-items-center">
                                    <input type="checkbox" id="isAvailable" name="is_available" value="1" checked style="width: 20px; height: 20px;">
                                    <label class="text-white fw-medium ms-2 mb-0" for="isAvailable" style="margin-left: 10px;">
                                        Available for sale
                                    </label>
                                </div>

                                <button type="submit" class="btn-primary w-100 shadow-lg glow-on-hover">
                                    <i class="fas fa-save me-2"></i> Save Product
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection