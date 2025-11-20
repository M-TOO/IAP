<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'GARI App')</title>
    
    <link rel="stylesheet" href="{{ asset('css/gari_welcome.css') }}">
    
    @livewireStyles
</head>
<body>
    <div class="hero-bg">
        <div class="overlay"></div>

        <main class="content-box-small">
             @yield('content') 
        </main>
    </div>

    @livewireScripts
</body>
</html>