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

<style>
    [x-cloak] { display: none !important; }

    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }

    .animate-bounce-slow {
        animation: bounce-slow 1.5s infinite;
    }
</style>

<body class="bg-[#f9f9f9] text-gray-900 font-sans flex flex-col min-h-screen">
        {{-- Верхняя навигация --}}
        <div class="container mx-auto px-4 lg:px-8 py-2 flex flex-wrap justify-between items-center text-sm text-gray-800">
            <div class="flex items-center gap-4 flex-wrap">
                <a href="#about" class="hover:underline">О нас</a>
                <a href="#contacts" class="hover:underline">Контакты</a>
            </div>
        </div>

        {{-- Основной блок шапки --}}
    <header class="sticky top-0 z-50 bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 lg:px-8 py-4 flex flex-col lg:flex-row justify-between items-center gap-4 lg:gap-12">
        {{-- Логотип и описание --}}
        <a href="{{ route('catalog') }}" class="flex items-center gap-4 min-w-0">
            <div class="text-5xl lg:text-6xl">🔥</div>
            <div class="min-w-0">
                <h1 class="text-xl lg:text-2xl font-extrabold uppercase leading-5 tracking-wide whitespace-nowrap">ШАШЛЫЧНЫЙ ДВОР</h1>
                <p class="text-sm lg:text-base text-red-500 font-medium">Лучшее мясо в г. Нягань</p>
            </div>
        </a>


            {{-- Инфо о доставке --}}
            <div class="text-center lg:text-left text-sm lg:text-base">
                <p class="font-semibold text-gray-700 uppercase">Доставка по городу</p>
                <p class="text-gray-700">30 мин · <span class="text-yellow-500 font-bold">★ 4.6</span></p>
            </div>

            {{-- Иконки профиля --}}
            <div class="flex items-center gap-6 text-sm text-gray-800 text-center">
                <a href="{{ route('catalog') }}"
                   class="flex flex-col items-center hover:text-red-600 transition">
                    <div class="text-2xl lg:text-3xl">🍖</div>
                    <span class="mt-1">Каталог</span>
                </a>
                @auth
                    @if(auth()->user()->can('access to manager panel') || 
                        auth()->user()->can('access to kitchen panel') || 
                        auth()->user()->can('access to courier panel'))
                        <a href="{{ route('dashboard') }}"
                           class="flex flex-col items-center hover:text-red-600 transition">
                            <div class="text-2xl lg:text-3xl">👤</div>
                            <span class="mt-1">Профиль</span>
                        </a>
                    @else
                        <a href="{{ route('chats.open', \App\Http\Controllers\ChatController::SUPPORT_USER_ID) }}"
                           class="flex flex-col items-center hover:text-red-600 transition">
                            <div class="text-2xl lg:text-3xl">🎧</div>
                            <span class="mt-1">Поддержка</span>
                        </a>
                        <a href="{{ route('profile_custom') }}"
                           class="flex flex-col items-center hover:text-red-600 transition">
                            <div class="text-2xl lg:text-3xl">👤</div>
                            <span class="mt-1">Профиль</span>
                        </a>
                    @endif

                    <!-- Кнопка Выход -->
                    <form method="POST" action="{{ route('logout') }}"
                          class="flex flex-col items-center hover:text-red-600 transition">
                        @csrf
                        <button type="submit" class="text-2xl lg:text-3xl">
                            🚪
                        </button>
                        <span class="mt-1">Выход</span>
                    </form>
                @else
                    <button onclick="openModal('loginModal')"
                       class="flex flex-col items-center hover:text-red-600 transition">
                        <div class="text-2xl lg:text-3xl">👤</div>
                        <span class="mt-1">Войти</span>
                    </button>
                @endauth

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

    <div id="loginModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-xl bg-white rounded-2xl shadow-2xl p-10 sm:p-12">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">Вход в аккаунт</h2>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Электронная почта</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 text-base">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 text-base">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                        Запомнить меня
                    </label>
                </div>

                <!-- Links -->
                <div class="flex flex-col sm:flex-row sm:justify-between gap-2 text-sm mt-2">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline">
                            Забыли пароль?
                        </a>
                    @endif
                    <button type="button" onclick="switchModal('loginModal', 'registerModal')" class="text-indigo-600 hover:underline">
                        Нет аккаунта? Зарегистрироваться
                    </button>
                </div>

                <!-- Buttons -->
                <div class="mt-8 flex justify-end gap-4">
                    <button type="button" onclick="closeModal('loginModal')"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Отмена
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm">
                        Войти
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно регистрации -->
<div id="registerModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-xl bg-white rounded-2xl shadow-2xl p-10 sm:p-12">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">Регистрация</h2>

            <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Имя</label>
                        <x-text-input id="first_name" class="block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                      type="text" name="first_name" required autofocus />
                        <div id="register-first-name-error" class="text-red-500 text-sm mt-1"></div>
                    </div>
                    <div>
                        <label for="second_name" class="block text-sm font-medium text-gray-700 mb-1">Фамилия</label>
                        <x-text-input id="second_name" class="block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                      type="text" name="second_name" required />
                        <div id="register-second-name-error" class="text-red-500 text-sm mt-1"></div>
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Логин</label>
                    <x-text-input id="name" class="block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                  type="text" name="name" required />
                    <div id="register-name-error" class="text-red-500 text-sm mt-1"></div>
                </div>

                <div>
                    <label for="register_email" class="block text-sm font-medium text-gray-700 mb-1">Электронная почта</label>
                    <x-text-input id="register_email" class="block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                  type="email" name="email" required />
                    <div id="register-email-error" class="text-red-500 text-sm mt-1"></div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Номер телефона</label>
                    <x-text-input id="phone" class="block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                  type="text" name="phone" required />
                    <div id="register-phone-error" class="text-red-500 text-sm mt-1"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
                        <x-text-input id="password" class="block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                      type="password" name="password" required />
                        <div id="register-password-error" class="text-red-500 text-sm mt-1"></div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Повтор пароля</label>
                        <x-text-input id="password_confirmation" class="block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                      type="password" name="password_confirmation" required />
                    </div>
                </div>

                <!-- Ссылки и кнопки -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-4">
                    <button type="button" onclick="switchModal('registerModal', 'loginModal')" 
                            class="text-sm text-indigo-600 hover:underline">
                        Уже есть аккаунт? Войти
                    </button>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('registerModal')" 
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Отмена
                        </button>
                        <button type="submit" 
                                class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm">
                            Зарегистрироваться
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>




<div
    x-data="{ show: @json(session('show_welcome')) }" 
    x-cloak
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-6 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-600"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    x-init="setTimeout(() => show = false, 2000)"
    class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-black/30"
>
    <div class="bg-white rounded-2xl shadow-2xl px-8 py-6 text-center max-w-sm w-full border border-gray-200 relative overflow-hidden">
        <!-- Glow background shape -->
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-indigo-500 opacity-20 rounded-full blur-2xl animate-pulse"></div>

        <!-- Icon with bounce -->
        <div class="mb-4 text-indigo-600 animate-bounce-slow">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-2">Вы успешно авторизовались</h2>
        <p class="text-gray-600 text-sm">Добро пожаловать 👋</p>
    </div>
</div>


    {{-- Скрипты --}}
    @yield('scripts')

    <script>
    // Функции для работы с модальными окнами
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        clearErrors(modalId);
    }

    function switchModal(fromModalId, toModalId) {
        closeModal(fromModalId);
        openModal(toModalId);
        clearErrors(toModalId);
    }

    function clearErrors(modalId) {
        const modal = document.getElementById(modalId);
        const errorElements = modal.querySelectorAll('[id$="-error"]');
        errorElements.forEach(el => el.textContent = '');
    }

    // Обработка успешного входа/регистрации
    @if (session('status') || $errors->any())
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                @if ($errors->has('email') || $errors->has('password'))
                    openModal('loginModal');
                @elseif ($errors->has('register_email') || $errors->has('password_confirmation'))
                    openModal('registerModal');
                @endif
            });
        @endif
    @endif

    @if (session('show_welcome'))
    <script>
        document.addEventListener('alpine:init', () => {
            const welcomeModal = document.getElementById('welcomeModal');
            if (welcomeModal) welcomeModal.__x.$data.show = true;
        });
    </script>
    @endif
    
    // Закрытие модального окна при клике вне его
    document.querySelectorAll('[id$="Modal"]').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });
    });
    </script>
</body>
</html>