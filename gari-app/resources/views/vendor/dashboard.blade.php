@extends('layouts.vendor')

@section('title', 'Vendor Overview')

@section('page-css')
<style>
    /* Stats Grid */
    .stats-grid {
        display: grid;
        /* UPDATED: Lowered min-width to 200px to fit better next to open sidebar */
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        display: flex; align-items: center; justify-content: space-between;
    }
    
    .stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
    }

    /* Product Grid */
    .products-grid {
        display: grid;
        /* UPDATED: Lowered min-width to 220px */
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.5rem;
    }

    .product-card {
        background: rgba(30, 35, 50, 0.6);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        display: flex; flex-direction: column;
        transition: transform 0.2s;
    }
    .product-card:hover { transform: translateY(-5px); border-color: var(--primary); }
    
    .product-img {
        height: 160px; width: 100%; object-fit: cover; background: #151925;
    }

    .bg-soft-primary { background: rgba(129, 140, 248, 0.1); color: var(--primary); }
    .bg-soft-success { background: rgba(52, 211, 153, 0.1); color: var(--success); }
    .bg-soft-warning { background: rgba(251, 191, 36, 0.1); color: var(--warning); }
</style>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 style="margin: 0; font-weight: 700;">Overview</h2>
            <p style="color: var(--text-muted); margin: 5px 0 0;">Welcome back, {{ Auth::user()->name ?? 'Vendor' }}</p>
        </div>
        <div style="background: rgba(255,255,255,0.05); padding: 8px 16px; border-radius: 20px; font-size: 0.9rem;">
            {{ now()->format('M d, Y') }}
        </div>
    </div>

    <div class="stats-grid">
        <div class="glass-card stat-card" style="margin:0;">
            <div>
                <p style="color:var(--text-muted); font-size:0.85rem; text-transform:uppercase; margin:0 0 5px;">Total Products</p>
                <h2 style="margin:0; font-size:1.8rem;">{{ $products->count() }}</h2>
            </div>
            <div class="stat-icon bg-soft-primary"><i class="fas fa-box"></i></div>
        </div>

        <div class="glass-card stat-card" style="margin:0;">
            <div>
                <p style="color:var(--text-muted); font-size:0.85rem; text-transform:uppercase; margin:0 0 5px;">Total Orders</p>
                <h2 style="margin:0; font-size:1.8rem;">0</h2>
            </div>
            <div class="stat-icon bg-soft-warning"><i class="fas fa-shopping-cart"></i></div>
        </div>

        <div class="glass-card stat-card" style="margin:0;">
            <div>
                <p style="color:var(--text-muted); font-size:0.85rem; text-transform:uppercase; margin:0 0 5px;">Revenue</p>
                <h2 style="margin:0; font-size:1.8rem;">$0.00</h2>
            </div>
            <div class="stat-icon bg-soft-success"><i class="fas fa-wallet"></i></div>
        </div>
    </div>

    <div class="glass-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; padding-bottom:1rem; border-bottom:1px solid var(--border-color);">
            <h3 style="margin:0; font-size:1.2rem;">Your Inventory</h3>
            <a href="{{ route('vendor.products.create') }}" style="background:var(--primary); color:white; padding:8px 16px; border-radius:6px; text-decoration:none; font-size:0.9rem; font-weight:600;">
                <i class="fas fa-plus me-1"></i> Add New
            </a>
        </div>

        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-img">
                        @else
                            <div class="product-img" style="display:flex; align-items:center; justify-content:center; color:var(--text-muted);">
                                <i class="fas fa-image fa-2x"></i>
                            </div>
                        @endif

                        <div style="padding: 1rem; display:flex; flex-direction:column; flex-grow:1;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                                <h4 style="margin:0; font-size:1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:120px;">{{ $product->name }}</h4>
                                <span style="font-size:0.8rem; background:rgba(255,255,255,0.1); padding:2px 8px; border-radius:4px;">{{ $product->car_brand }}</span>
                            </div>
                            
                            <p style="color:var(--text-muted); font-size:0.85rem; margin-bottom:1rem; line-height:1.4;">
                                {{ Str::limit($product->description, 40) }}
                            </p>

                            <div style="margin-top:auto; display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--border-color); padding-top:0.8rem;">
                                <span style="font-weight:bold; color:var(--success);">${{ number_format($product->price, 2) }}</span>
                                
                                <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this product?')" style="background:transparent; border:none; color:var(--danger); cursor:pointer;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center; padding:3rem; color:var(--text-muted);">
                <i class="fas fa-box-open fa-3x" style="opacity:0.3; margin-bottom:1rem;"></i>
                <p>No products found in your inventory.</p>
                <a href="{{ route('vendor.products.create') }}" style="color:var(--primary);">Add your first product</a>
            </div>
        @endif
    </div>
@endsection