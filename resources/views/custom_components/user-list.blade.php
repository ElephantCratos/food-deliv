<x-app-layout>
    <div class="py-12 flex justify-center items-center" style="margin: 50px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5 w-full">

                <!-- Заголовок страницы -->
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Список пользователей</h1>

                <!-- Фильтрация и поиск -->
                <div class="flex justify-between items-center mb-6">
                    <!-- Поиск -->
                    <div class="w-1/2">
                        <input
                          type="text"
                          id="search"
                          placeholder="Поиск пользователя..."
                          class="px-4 py-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                          onkeyup="filterUsers()"
                        />
                    </div>
                    <!-- Фильтр по ролям -->
                    <div class="w-1/4">
                        <select
                          id="role-filter"
                          class="px-4 py-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                          onchange="filterUsers()"
                        >
                            <option value="">Все роли</option>
                            <option value="2">Менеджеры</option>
                            <option value="4">Курьеры</option>
                        </select>
                    </div>
                </div>

                <!-- Список пользователей -->
                <ul id="user-list">
                    @foreach ($users as $user)
                        <li
                          class="user-item mb-4"
                          data-roles="{{ $user->roles->pluck('id')->implode(',') }}"
                        >
                            <a
                              href="{{ route('chats.open', $user->id) }}"
                              class="bg-white hover:bg-gray-300 rounded-lg py-2 px-4 ml-4 flex items-center"
                            >
                                <span class="mr-3">{{ $user->name }}</span>
                                <span class="text-sm text-gray-500">
                                    {{ $user->roles->pluck('name')->implode(', ') }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Скрипт фильтрации -->
    <script>
    function filterUsers() {
        const q = document.getElementById('search').value.toLowerCase();
        const role = document.getElementById('role-filter').value;
        document.querySelectorAll('.user-item').forEach(item => {
            const name = item.querySelector('a').textContent.toLowerCase();
            const roles = item.dataset.roles.split(',');
            const okName = name.includes(q);
            const okRole = !role || roles.includes(role);
            item.style.display = okName && okRole ? 'block' : 'none';
        });
    }
    </script>
</x-app-layout>
