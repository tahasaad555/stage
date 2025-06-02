<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriTerre Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-green-100">
                <i class="fas fa-leaf text-green-600 text-2xl"></i>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                AgriTerre Admin
            </h2>
            <p class="mt-2 text-gray-600">
                Agricultural Land Management System
            </p>
        </div>

        <div class="space-y-4">
            @if(auth()->check())
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-700 mb-4">
                        Welcome back, <strong>{{ auth()->user()->full_name }}</strong>!
                    </p>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Go to Admin Dashboard
                        </a>
                    @endif
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-700 mb-4">
                        Please login to access the admin panel.
                    </p>
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </a>
                </div>
            @endif
        </div>

        <div class="text-sm text-gray-500">
            <p>Default Admin Credentials:</p>
            <p class="font-mono">admin@agriterre.com / password123</p>
        </div>
    </div>
</body>
</html>