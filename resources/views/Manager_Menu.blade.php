<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 justify-center items-center " style="display:flex;">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg items-center p-5 w-full">
                <table class="bg-gray-700 w-full">
                    <tr class="">
                        <th class="border-2 border-slate-300 p-5 text-white text-center">Id</th>
                        <th class="border-2 border-slate-300 p-5 text-white text-center">Название блюда</th>
                        <th class="border-2 border-slate-300 p-5 text-white text-center">Изображение</th>
                        <th class="border-2 border-slate-300 p-5 text-white text-center">Топинги</th>
                        <th class="border-2 border-slate-300 p-5 text-white text-center">Цена</th>
                        <th class="border-2 border-slate-300 p-5 text-white text-center">Изменение</th>
                    </tr>
                    <tbody>
                    @foreach ($Dish as $dish)
                        <tr class="items-center">
                            <td class="border-2 border-slate-300 p-5 text-white text-center">{{ $dish->id }}</td>
                            <td class="border-2 border-slate-300 p-5 text-white text-center">{{ $dish->name }}</td>
                            <td class="border-2 border-slate-300 p-5 text-white text-center">{{ $dish->image_path }}</td>
                            <td class="border-2 border-slate-300 p-5 text-white text-center"> @if ($dish->ingredients->isNotEmpty())
                                    @foreach ($dish->ingredients as $ingredient)
                                        {{ $ingredient->name }}@if (!$loop->last), @endif
                                    @endforeach
                                @else
                                    <em>No ingredients found</em>
                                @endif</td>
                            <td class="border-2 border-slate-300 p-5 text-white text-center">{{ $dish->price }}</td>
                            <td class="border-2 border-slate-300 p-5 text-white text-center">
                                <form method="get" action="{{ route('dish.edit', $dish->id) }}">
                                        <button type="submit" class="text-blue-500 underline">Изменить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    <a href="{{ route('Edit_menu') }}" class="text-green-500 underline">Добавить новый блок</a>
                </div>
            </div>
        </div>
    </div>
   
</x-app-layout>
