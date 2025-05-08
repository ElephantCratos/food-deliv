<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight pt-24">Менеджеры и курьеры</h2>
    </x-slot>

    <div class="py-4 px-6">
        <a href="{{ route('user-management.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Добавить</a>

        @if(session('success'))
            <div class="text-green-500 mt-2">{{ session('success') }}</div>
        @endif

        <table class="mt-4 w-full border">
            <thead>
                <tr>
                    <th class="border p-2">Имя</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Роль</th>
                    <th class="border p-2">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="border p-2">{{ $user->name }}</td>
                    <td class="border p-2">{{ $user->email }}</td>
                    <td class="border p-2">{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                    <td class="border p-2">
                        <form action="{{ route('user-management.destroy', $user) }}" method="POST" onsubmit="return confirm('Удалить пользователя?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-2 py-1 rounded">Удалить</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
