<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Food Delivery')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
</head>

<body class="flex flex-col min-h-screen bg-gray-50 text-gray-900">

    {{-- Header --}}
    <header class="bg-yellow-500 shadow-md py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="{{ route('catalog') }}" class="text-white text-2xl font-bold hover:text-yellow-200 transition">
                🍔 FoodieDelivery
            </a>

            <nav>
                <ul class="flex items-center space-x-6">
                    @section('nav')
                        <li><a href="{{ route('profile.edit') }}" class="text-white hover:underline">Профиль</a></li>
                        <li><a href="{{ route('catalog') }}" class="text-white hover:underline">Меню</a></li>

                        {{-- Cart button --}}
                        <li>
                            <a href="{{ route('Cart') }}" class="relative text-white hover:underline">
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
    <footer class="bg-yellow-500 py-4 mt-auto shadow-inner">
        <div class="container mx-auto px-4 text-center text-white text-sm">
            <p>@yield('footer', '© ' . date('Y') . ' FoodieDelivery. Все права защищены.')</p>
        </div>
    </footer>

</body>

</html>
