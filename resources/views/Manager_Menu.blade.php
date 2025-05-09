<x-app-layout>
<div class="pt-24 pb-12">
            <!-- Основной контент -->
            <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto py-10">
                <h1 class="text-3xl font-bold mb-6">Список блюд</h1>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 border-b font-medium text-gray-700">ID</th>
                            <th class="p-3 border-b font-medium text-gray-700">Название блюда</th>
                            <th class="p-3 border-b font-medium text-gray-700">Изображение</th>
                            <th class="p-3 border-b font-medium text-gray-700">Категория</th>
                            <th class="p-3 border-b font-medium text-gray-700">Цена</th>
                            <th class="p-3 border-b font-medium text-gray-700">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dishes as $dish)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border-b">{{ $dish->id }}</td>
                                <td class="p-3 border-b">{{ $dish->name }}</td>
                                <td class="p-3 border-b text-center">
                                    <img src="{{ asset($dish->image_path) }}" alt="Фото блюда" class="w-16 h-16 object-cover rounded">
                                </td>
                                <td class="p-3 border-b">{{ $dish->category->name }}</td>
                                <td class="p-3 border-b">{{ $dish->price }} ₽</td>
                                <td class="p-3 border-b space-y-1">
                                    <form method="GET" action="{{ route('dish.edit', $dish->id) }}">
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Изменить</button>
                                    </form>
                                    <form method="POST" action="{{ route('dish.delete', $dish->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6">
                    <a href="{{ route('Edit_menu') }}" class="text-orange-500 hover:text-orange-700 font-medium">
                        ➕ Добавить новое блюдо
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
