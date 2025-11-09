<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/admin.css'])
</head>
<body>
<div class="page-wrapper">
    <div class="container">
        <header class="admin-header">
            <div class="admin-header-left">
                <a href="{{ route('admin.tickets.index') }}" class="admin-logo">
                    CRM Widget
                </a>
            </div>
            <div class="admin-header-right">
                @auth
                    <span class="admin-user">{{ auth()->user()->email }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-button">Logout</button>
                    </form>
                @endauth
            </div>
        </header>
        @yield('content')
    </div>
</div>
</body>
</html>
