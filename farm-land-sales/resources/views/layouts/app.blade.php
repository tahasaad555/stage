<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Land & Farming Materials Platform</title>
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>AgriLand</h1>
            </div>
         <nav>
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="{{ route('lands.search') }}">Agricultural Lands</a></li>
        <li><a href="{{ route('equipment.search') }}">Farm Equipment</a></li>
        <li><a href="{{ route('announcements.create') }}">Create Listing</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle">My Account</a>
            <div class="dropdown-menu">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('favorites') }}">My Favorites</a>
                <a href="{{ route('messages.inbox') }}">Messages</a>
                <a href="{{ route('transactions') }}">Transactions</a>
              <a href="{{ route('notifications') }}" class="notification-link">
   
             Notifications
    @php
        $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                                             ->where('lu', false)
                                             ->count();
    @endphp
    
    @if($unreadCount > 0)
        <span class="notification-badge">{{ $unreadCount }}</span>
    @endif
</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </li>
    </ul>
</nav>
            <div class="user-menu">
                <!-- Will be dynamic based on authentication -->
                @auth
                    <a href="/dashboard">Dashboard</a>
                    <a href="/messages">Messages</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a href="/login">Login</a>
                    <a href="/register">Register</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            {{ $slot }}
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} AgriLand. All rights reserved.</p>
        </div>
    </footer>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>