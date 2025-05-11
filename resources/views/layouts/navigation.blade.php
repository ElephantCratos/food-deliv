<nav x-data="{ open: false }" class="bg-white text-black border-b border-gray-200 shadow-sm z-50 fixed top-0 w-full backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Логотип -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('catalog') }}">
                    <x-application-logo class="block h-10 w-auto fill-current text-orange-600" />
                </a>
            </div>

            <!-- Навигация -->
            <div class="hidden sm:flex space-x-6">
                @auth
                    @can('access to manager panel')
                        <x-nav-link :href="route('Customer_Orders')" :active="request()->routeIs('Customer_Orders')">
                            {{ __('Заказы') }}
                        </x-nav-link>
                    @endcan

                    @can('access to kitchen panel')
                        <x-nav-link :href="route('Kitchen_Orders')" :active="request()->routeIs('Kitchen_Orders')">
                            {{ __('Кухня') }}
                        </x-nav-link>
                    @endcan

                    @can('access to manager panel')
                        <x-nav-link :href="route('All_Orders')" :active="request()->routeIs('All_Orders')">
                            {{ __('Все заказы') }}
                        </x-nav-link>
                    @endcan

                    @can('access to courier panel')
                        <x-nav-link :href="route('Courier_Orders')" :active="request()->routeIs('Courier_Orders')">
                            {{ __('Курьер') }}
                        </x-nav-link>
                    @endcan

                    @can('access to manager panel')
                        <x-nav-link :href="route('Manager_Menu')" :active="request()->routeIs('Manager_Menu')">
                            {{ __('Меню') }}
                        </x-nav-link>
                    @endcan

                    @can('access to manager panel')
                        <x-nav-link :href="route('user-management.index')" :active="request()->routeIs('user-management.index')"> 
                            {{ __('Добавление менеджера/курьера') }} 
                        </x-nav-link>
                    @endcan

                    @can('access to manager panel')
                        <x-nav-link :href="route('Manager_Promocodes')" :active="request()->routeIs('Manager_Promocodes')"> 
                            {{ __('Промокоды') }} 
                        </x-nav-link>
                    @endcan

                    @can('access to manager panel')
                        <x-nav-link :href="route('chats.index')" :active="request()->routeIs('chats.index')"> 
                            {{ __('Чат поддержки') }} 
                        </x-nav-link>
                    @endcan
                @endauth
            </div>

            <!-- Настройки пользователя -->
            <div class="hidden sm:flex items-center space-x-4">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center space-x-2 bg-white white:bg-gray-800 px-4 py-2 rounded-lg shadow-sm text-sm text-dark-700 white:text-dark-300 hover:bg-gray-100 white:hover:bg-gray-900 transition">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Профиль') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('catalog')">
                                {{ __('Каталог') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Выйти') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <button onclick="openModal('loginModal')" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                        Войти
                    </button>
                    <button onclick="openModal('registerModal')" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded shadow">
                        Регистрация
                    </button>
                @endauth
            </div>

            <!-- Мобильное меню -->
            <div class="flex sm:hidden">
                <button @click="open = !open" class="p-2 rounded-md text-gray-500 hover:text-orange-500 hover:bg-orange-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Мобильное раскрывающееся меню -->
    <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden bg-white dark:bg-gray-800 px-4 pb-4 pt-2 rounded-b-lg shadow-md">
        @auth
            <div class="mb-4">
                <div class="text-gray-800 dark:text-gray-100 font-semibold">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Панель') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Профиль') }}
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Выйти') }}
                </x-responsive-nav-link>
            </form>
        @else
            <x-responsive-nav-link href="#" onclick="openModal('loginModal')">
                {{ __('Войти') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="#" onclick="openModal('registerModal')">
                {{ __('Регистрация') }}
            </x-responsive-nav-link>
        @endauth

        <!-- Мобильные ссылки для меню -->
        @auth
            @can('access to manager panel')
                <x-responsive-nav-link :href="route('Customer_Orders')" :active="request()->routeIs('Customer_Orders')">
                    {{ __('Заказы') }}
                </x-responsive-nav-link>
            @endcan

            @can('access to kitchen panel')
                <x-responsive-nav-link :href="route('Kitchen_Orders')" :active="request()->routeIs('Kitchen_Orders')">
                    {{ __('Кухня') }}
                </x-responsive-nav-link>
            @endcan

            @can('access to manager panel')
                <x-responsive-nav-link :href="route('All_Orders')" :active="request()->routeIs('All_Orders')">
                    {{ __('Все заказы') }}
                </x-responsive-nav-link>
            @endcan

            @can('access to courier panel')
                <x-responsive-nav-link :href="route('Courier_Orders')" :active="request()->routeIs('Courier_Orders')">
                    {{ __('Курьер') }}
                </x-responsive-nav-link>
            @endcan

            @can('access to manager panel')
                <x-responsive-nav-link :href="route('Manager_Menu')" :active="request()->routeIs('Manager_Menu')">
                    {{ __('Меню') }}
                </x-responsive-nav-link>
            @endcan

            @can('access to manager panel')
                <x-responsive-nav-link :href="route('user-management.index')" :active="request()->routeIs('user-management.index')"> 
                    {{ __('Добавление менеджера/курьера') }} 
                </x-responsive-nav-link>
            @endcan

            @can('access to manager panel')
                <x-responsive-nav-link :href="route('Manager_Promocodes')" :active="request()->routeIs('Manager_Promocodes')"> 
                    {{ __('Промокоды') }} 
                </x-responsive-nav-link>
            @endcan

            @can('access to manager panel')
                <x-responsive-nav-link :href="route('chats.index')" :active="request()->routeIs('chats.index')"> 
                    {{ __('Чат поддержки') }} 
                </x-responsive-nav-link>
            @endcan
        @endauth
    </div>
</nav>

