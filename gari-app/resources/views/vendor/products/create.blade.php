@extends('layouts.vendor')

@section('title', 'Add New Product')

@section('page-css')
<style>
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 2rem;
    }
    @media(max-width: 900px) { .form-grid { grid-template-columns: 1fr; } }

    .input-group { margin-bottom: 1.25rem; }
    
    .label {
        display: block;
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .input, .textarea, .select {
        width: 100%;
        background: var(--input-bg);
        border: 1px solid var(--border-color);
        color: white;
        padding: 0.8rem;
        border-radius: 8px;
        box-sizing: border-box; 
    }
    .input:focus, .textarea:focus, .select:focus {
        outline: none; border-color: var(--primary);
    }

    .upload-box {
        border: 2px dashed var(--border-color);
        border-radius: 10px; padding: 2rem; text-center;
        cursor: pointer; transition: all 0.2s;
    }
    .upload-box:hover { border-color: var(--primary); background: rgba(129, 140, 248, 0.05); }

    .btn-save {
        width: 100%;
        background: var(--primary);
        color: white;
        padding: 1rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 1rem;
    }
    .btn-save:hover { opacity: 0.9; }
</style>
@endsection

@section('content')
    <div style="margin-bottom: 2rem;">
        <h2 style="margin: 0; font-weight: 700;">Add New Product</h2>
        <p style="color: var(--text-muted); margin: 5px 0 0;">Fill in the details to list a new spare part.</p>
    </div>

    <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-grid">
            <div class="glass-card" style="margin:0;">
                <div class="input-group">
                    <label class="label">Product Name</label>
                    <input type="text" name="name" class="input" placeholder="e.g. Ceramic Brake Pads" required>
                </div>

                <div class="input-group">
                    <label class="label">Description</label>
                    <textarea name="description" class="textarea" rows="6" placeholder="Detailed technical specifications..." required></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="input-group">
                        <label class="label">Price ($)</label>
                        <input type="number" name="price" class="input" step="0.01" placeholder="0.00" required>
                    </div>
                    <div class="input-group">
                        <label class="label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="input" placeholder="10" required>
                    </div>
                </div>
            </div>

            <div>
                <div class="glass-card" style="margin-bottom: 1.5rem;">
                    <div class="input-group">
                        <label class="label">Car Brand Compatibility</label>
                        <select name="car_brand" class="select" required>
                            <option value="" disabled selected>Select Brand...</option>
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

                    <div class="input-group">
                        <label class="label">Product Image</label>
                        <div class="upload-box" onclick="document.getElementById('fileInput').click()">
                            <input type="file" id="fileInput" name="image" class="d-none" style="display:none;" onchange="document.getElementById('fileName').innerText = this.files[0].name">
                            <i class="fas fa-cloud-upload-alt fa-2x text-primary" style="margin-bottom:10px; color:var(--primary);"></i>
                            <div style="font-weight:600;">Click to Upload</div>
                            <div id="fileName" style="font-size:0.8rem; color:var(--text-muted); margin-top:5px;">No file selected</div>
                        </div>
                    </div>
                    
                    <div class="input-group" style="display:flex; align-items:center; gap:10px;">
                        <input type="checkbox" id="isAvailable" name="is_available" value="1" checked style="width:18px; height:18px;">
                        <label for="isAvailable" style="color:white; cursor:pointer;">Available for sale</label>
                    </div>
                </div>

                <button type="submit" class="btn-save">
                    <i class="fas fa-save me-2"></i> Save Product
                </button>
            </div>
        </div>
    </form>
@endsection