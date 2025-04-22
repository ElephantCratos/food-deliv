<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Food Delivery')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    @yield('head')

</head>

<body class="flex flex-col min-h-screen bg-gray-50 text-gray-900">

    {{-- Header --}}
    <header class="py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="{{ route('catalog') }}" class="text-gray-900 text-2xl font-bold hover:text-blue-600 transition">
                🍔 FoodieDelivery
            </a>

            <nav>
                <ul class="flex items-center space-x-6">
                    @section('nav')
                        <li><a href="{{ route('profile.edit') }}" class="hover:underline text-gray-900">Профиль</a></li>
                        <li><a href="{{ route('catalog') }}" class="hover:underline text-gray-900">Меню</a></li>

                        {{-- Cart button --}}
                        <li>
                            <a href="{{ route('Cart') }}" class="relative hover:underline text-gray-900">
                                🛒 Корзина
                                @if(session()->has('cart') && count(session('cart')) > 0)
                                    <span class="absolute -top-2 -right-3 bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">
                                        {{ count(session('cart')) }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @show
                </ul>
            </nav>
        </div>
    </header>

    {{-- Main content --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer-effect">
        <div class="container mx-auto px-4">
            <p>@yield('footer', '© ' . date('Y') . ' FoodieDelivery. Все права защищены.')</p>
            <p class="mt-2">
                <a href="#" class="hover:underline">Политика конфиденциальности</a> |
                <a href="#" class="hover:underline">Условия использования</a>
            </p>
        </div>
    </footer>

</body>

</html>
