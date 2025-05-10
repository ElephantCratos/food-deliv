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
                <a href="#" class="hover:underline">О нас</a>
                <a href="#" class="hover:underline">Контакты</a>
                <a href="#" class="hover:underline">Корпоративные заказы</a>
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
                <a href="{{ route('chats.open', \App\Http\Controllers\ChatController::SUPPORT_USER_ID) }}"
                   class="flex flex-col items-center hover:text-red-600 transition">
                    <div class="text-2xl lg:text-3xl">🎧</div>
                    <span class="mt-1">Поддержка</span>
                </a>
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
        <a href="{{ route('profile_custom') }}"
           class="flex flex-col items-center hover:text-red-600 transition">
            <div class="text-2xl lg:text-3xl">👤</div>
            <span class="mt-1">Профиль</span>
        </a>
    @endif
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

    <!-- Модальное окно входа -->
<div id="loginModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Вход в аккаунт</h3>
                
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                
                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mb-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            @if (Route::has('password.request'))
                                <a class="text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                            <button type="button" onclick="switchModal('loginModal', 'registerModal')" 
                                    class="ml-2 text-sm text-indigo-600 hover:text-indigo-500">
                                Нет аккаунта? Зарегистрироваться
                            </button>
                        </div>
                        <div>
                            <button type="button" onclick="closeModal('loginModal')" 
                                    class="mr-2 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Отмена
                            </button>
                            <x-primary-button type="submit">
                                {{ __('Log in') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Модальное окно регистрации -->
    <div id="registerModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Регистрация</h3>
                    <form id="registerForm" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" />
                                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" required autofocus />
                                <div id="register-first-name-error" class="text-red-500 text-xs mt-1"></div>
                            </div>
                            <div>
                                <x-input-label for="second_name" :value="__('Second Name')" />
                                <x-text-input id="second_name" class="block mt-1 w-full" type="text" name="second_name" required />
                                <div id="register-second-name-error" class="text-red-500 text-xs mt-1"></div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required />
                            <div id="register-name-error" class="text-red-500 text-xs mt-1"></div>
                        </div>
                        <div class="mb-4">
                            <x-input-label for="register_email" :value="__('Email')" />
                            <x-text-input id="register_email" class="block mt-1 w-full" type="email" name="email" required />
                            <div id="register-email-error" class="text-red-500 text-xs mt-1"></div>
                        </div>
                        <div class="mb-4">
                            <x-input-label for="phone" :value="__('Phone Number')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" required />
                            <div id="register-phone-error" class="text-red-500 text-xs mt-1"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                                <div id="register-password-error" class="text-red-500 text-xs mt-1"></div>
                            </div>
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <button type="button" onclick="switchModal('registerModal', 'loginModal')" 
                                    class="text-sm text-indigo-600 hover:text-indigo-500">
                                Уже есть аккаунт? Войти
                            </button>
                            <div>
                                <button type="button" onclick="closeModal('registerModal')" 
                                        class="mr-2 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Отмена
                                </button>
                                <x-primary-button type="submit">
                                    {{ __('Register') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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