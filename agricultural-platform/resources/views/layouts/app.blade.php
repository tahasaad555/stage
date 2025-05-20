<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Système de Gestion Agricole</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c7d43;
            --secondary-color: #154d23;
            --accent-color: #f39c12;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background-color: var(--primary-color) !important;
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .footer {
            background-color: #343a40;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
        }
        
        .nav-link:hover {
            color: white !important;
        }
        
        .dropdown-menu {
            border-radius: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn {
            border-radius: 0.25rem;
        }
        
        /* Custom utilities */
        .hover-scale {
            transition: transform 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
        .bg-light-green {
            background-color: rgba(44, 125, 67, 0.1);
        }
    </style>
    @yield('head')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-leaf me-2"></i> AgriTerre
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('terres.index') }}">Terres Agricoles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('annonces.index') }}">Annonces</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="registerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Inscription
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="registerDropdown">
                                <li><a class="dropdown-item" href="{{ route('register.client') }}">Client</a></li>
                                <li><a class="dropdown-item" href="{{ route('register.fournisseur') }}">Fournisseur</a></li>
                            </ul>
                        </li>
                    @else
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Tableau de bord</a>
                            </li>
                        @elseif(auth()->user()->isClient())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('client.dashboard') }}">Tableau de bord</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('panier.show') }}">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                            </li>
                        @elseif(auth()->user()->isFournisseur())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('fournisseur.dashboard') }}">Tableau de bord</a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ auth()->user()->nom }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                @if(auth()->user()->isClient())
                                    <li><a class="dropdown-item" href="{{ route('client.profile') }}">Mon profil</a></li>
                                    <li><a class="dropdown-item" href="{{ route('client.transactions') }}">Mes transactions</a></li>
                                    <li><a class="dropdown-item" href="{{ route('client.messages') }}">Messages</a></li>
                                @elseif(auth()->user()->isFournisseur())
                                    <li><a class="dropdown-item" href="{{ route('fournisseur.profile') }}">Mon profil</a></li>
                                    <li><a class="dropdown-item" href="{{ route('fournisseur.annonces') }}">Mes annonces</a></li>
                                    <li><a class="dropdown-item" href="{{ route('fournisseur.transactions') }}">Mes transactions</a></li>
                                    <li><a class="dropdown-item" href="{{ route('fournisseur.messages') }}">Messages</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>AgriTerre</h5>
                    <p>Votre plateforme de gestion des terres agricoles et produits agricoles.</p>
                </div>
                <div class="col-md-4">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li><a href="{{ route('terres.index') }}" class="text-white">Terres Agricoles</a></li>
                        <li><a href="{{ route('annonces.index') }}" class="text-white">Annonces</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <address>
                        <i class="fas fa-map-marker-alt"></i> 123 Avenue de l'Agriculture<br>
                        75000 Paris, France<br>
                        <i class="fas fa-phone"></i> +33 1 23 45 67 89<br>
                        <i class="fas fa-envelope"></i> contact@agriterre.fr
                    </address>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center mt-3">
                    <p class="mb-0">&copy; {{ date('Y') }} AgriTerre. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>