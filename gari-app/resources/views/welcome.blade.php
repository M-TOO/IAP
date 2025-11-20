<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GARI App | Home</title>

    <link rel="stylesheet" href="{{ asset('css/gari_welcome.css') }}">

    <style>
        .hero-bg {
            /* 🔑 Set the default background image path here */
            background-image: url('{{ asset('images/background.jpg') }}'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
    </style>

</head>
<body>
    <div class="hero-bg">
        <div class="overlay"></div>

        <div class="content-box">
            
            <h1>Welcome to <span class="platform-name">GARI</span> 🚗</h1>
            
            <p class="tagline">
                Your premier platform for genuine Spare Parts and verified Vendors.
            </p>

            <div class="description">
                GARI revolutionizes the auto parts market. Customers can effortlessly search for specific parts by name or car brand and connect with verified vendors. Vendors gain a powerful, moderated digital shopfront, all ensured by transparent Admin oversight.
            </div>

            <h2>Get Started</h2>

            <div class="cta-grid">
                
                <div class="cta-card" style="border-left: 5px solid var(--color-accent);">
                    <p>Customer</p>
                    <a href="{{ route('customer.register') }}" class="btn btn-primary">
                        Create Account
                    </a>
                    <a href="{{ route('login') }}" class="link-secondary">
                        Sign In Now
                    </a>
                </div>

                <div class="cta-card" style="border-left: 5px solid var(--color-vendor);">
                    <p>Spare Part Vendor</p>
                    <a href="{{ route('vendor.register') }}" class="btn btn-vendor">
                        Create Shop Account
                    </a>
                    <a href="{{ route('vendor.login') }}" class="link-secondary-vendor">
                        Sign In Now
                    </a>
                </div>

                <div class="cta-card" style="border-left: 5px solid var(--color-admin);">
    <p>Administrator</p>
    <a href="{{ route('admin.login') }}" class="btn btn-admin">
        Admin Login
    </a>
    <p class="small-text">Access system management</p>
</div>
            </div>
        </div>
    </div>
</body>
</html>