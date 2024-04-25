<x-app-layout>
    <div class="py-12 flex justify-center items-center" style="margin: 60px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5 w-full">
                <h1 class="text-xl p-4 text-gray-900 mb-4">Список пользователей</h1>
                <ul>
                    @foreach ($users as $user)
                        <li class="mb-4"><a href="/chat/{{ $user->id }}" class="bg-white hover:bg-gray-300 rounded-lg py-2 px-4 ml-4 text-gray-900">{{ $user->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
