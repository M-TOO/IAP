<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GARI App')</title>
    @yield('page-css')

    {{-- Default CSS for all pages EXCEPT customer dashboard --}}
    @if (!Request::is('customer/dashboard'))
        <link rel="stylesheet" href="{{ asset('css/gari_welcome.css') }}">
    @endif

    {{-- Customer Dashboard custom CSS --}}
    @yield('customer-dashboard-css')

    <style>
        .hero-bg {
            background-image: url('{{ asset('images/background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            position: relative;
        }
        .overlay {
            position: absolute;
            inset: 0;
            background-color: rgba(0,0,0,0.55);
            backdrop-filter: blur(1px);
            pointer-events: none;
        }
    </style>

    @livewireStyles
</head>

<body>

    {{-- Landing / Login / Register / Welcome Pages --}}
    @if (!Request::is('customer/dashboard'))
        <div class="hero-bg">
            <div class="overlay"></div>

            <main class="@yield('content-class', 'content-box-small') @yield('additional-classes', '')">
                @yield('content')
            </main>
        </div>

    {{-- Customer Dashboard page --}}
    @else
        @yield('content')
    @endif

    @livewireScripts
</body>
</html>
