<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AgriLand') }}</title>
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <header class="guest-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/">AgriLand</a>
                </div>
                <nav class="guest-nav">
                    <ul>
                        <li><a href="{{ route('lands.search') }}">Lands</a></li>
                        <li><a href="{{ route('equipment.search') }}">Equipment</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}" class="button button-small">Register</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="guest-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h2>AgriLand</h2>
                    <p>Your one-stop platform for agricultural lands and farming equipment.</p>
                </div>
                <div class="footer-links">
                    <div class="footer-column">
                        <h3>Explore</h3>
                        <ul>
                            <li><a href="{{ route('lands.search') }}">Agricultural Lands</a></li>
                            <li><a href="{{ route('equipment.search') }}">Farm Equipment</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h3>Account</h3>
                        <ul>
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h3>Contact</h3>
                        <ul>
                            <li>Email: info@agriland.com</li>
                            <li>Phone: +123 456 7890</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} AgriLand. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>