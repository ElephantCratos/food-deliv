<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
</head>

<body class="flex flex-col min-h-screen m-0">
    <header class="bg-yellow-500 py-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-white text-xl">Welcome to Our Food Delivery Catalog</h1>
            <nav>
                <ul class="flex">
                    @section('nav')
                    <li class="mx-4"><a href="{{ route('profile.edit') }}" class="text-white">Profile</a></li>
                    <li class="mx-4"><a href="#" class="text-white">Menu</a></li>
                    <li class="mx-4"><a href="#" class="text-white">Order Now</a></li>
                    <li class="mx-4"><a href="#" class="text-white">Contact Us</a></li>
                    @show
                </ul>
            </nav>
        </div>
    </header>

    @yield('content')

    <footer class="bg-yellow-500 py-4 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <p class="text-white">@yield('footer')</p>
        </div>
    </footer>
</body>

</html>