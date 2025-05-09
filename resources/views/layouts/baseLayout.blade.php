<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Food Delivery')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    @yield('head')
</head>

<body class="bg-[#f9f9f9] text-gray-900 font-sans flex flex-col min-h-screen">
        {{-- Верхняя навигация --}}
        <div class="container mx-auto px-4 lg:px-8 py-2 flex flex-wrap justify-between items-center text-sm text-gray-800">
            <div class="flex items-center gap-4 flex-wrap">
                <a href="{{ route('kitchen.camera') }}" class="text-red-600 font-semibold hover:underline">🔴 Кухня LIVE</a>
                <a href="#" class="hover:underline">О нас</a>
                <a href="#" class="hover:underline">Контакты</a>
                <a href="#" class="hover:underline">Корпоративные заказы</a>
            </div>
        </div>

        {{-- Основной блок шапки --}}
    <header class="sticky top-0 z-50 bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 lg:px-8 py-4 flex flex-col lg:flex-row justify-between items-center gap-4 lg:gap-12">
            {{-- Логотип и описание --}}
            <div class="flex items-center gap-4 min-w-0">
                <div class="text-5xl lg:text-6xl">🔥</div>
                <div class="min-w-0">
                    <h1 class="text-xl lg:text-2xl font-extrabold uppercase leading-5 tracking-wide whitespace-nowrap">ШАШЛЫЧНЫЙ ДВОР</h1>
                    <p class="text-sm lg:text-base text-red-500 font-medium">Лучшее мясо в г. Нягань</p>
                </div>
            </div>

            {{-- Инфо о доставке --}}
            <div class="text-center lg:text-left text-sm lg:text-base">
                <p class="font-semibold text-gray-700 uppercase">Доставка по городу</p>
                <p class="text-gray-700">30 мин · <span class="text-yellow-500 font-bold">★ 4.6</span></p>
            </div>

            {{-- Иконки профиля --}}
            <div class="flex items-center gap-6 text-sm text-gray-800 text-center">
                <a href="{{ route('chats.open', \App\Http\Controllers\ChatController::SUPPORT_USER_ID) }}"
                   class="flex flex-col items-center hover:text-red-600 transition">
                    <div class="text-2xl lg:text-3xl">🎧</div>
                    <span class="mt-1">Поддержка</span>
                </a>
                <a href="#"
                   class="flex flex-col items-center hover:text-red-600 transition">
                    <div class="text-2xl lg:text-3xl">🎟️</div>
                    <span class="mt-1">Мои акции</span>

                </a>
                <a href="{{ route('profile_custom') }}"
                   class="flex flex-col items-center hover:text-red-600 transition">
                    <div class="text-2xl lg:text-3xl">👤</div>
                    <span class="mt-1">Профиль</span>
                </a>
            </div>
        </div>

        {{-- Категории и корзина --}}
        <div class="container mx-auto px-4 lg:px-8 pt-3 pb-4 border-t flex flex-wrap items-center gap-4 lg:gap-6">
            @isset($categoriesList)
                <x-categories :categoriesList="$categoriesList" />
            @endisset

            {{-- Кнопка корзины --}}
            <a href="{{ route('Cart') }}"
               class="ml-auto bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-5 py-2 rounded-full transition whitespace-nowrap">
                Корзина
            </a>
        </div>
    </header>

    {{-- Контент страницы --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Футер --}}
    <footer class="bg-gray-100 text-center py-4">
        @yield('footer')
    </footer>

    {{-- Скрипты --}}
    @yield('scripts')
</body>

</html>
