<x-app-layout>
<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 justify-center items-center " style="display:flex;">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg items-center p-5 w-full">
                <table class="bg-gray-700 w-full">
    <tr class="">
        <th class="border-2 border-slate-300 p-5 text-white text-center">Название ингредиента</th>
        <th class="border-2 border-slate-300 p-5 text-white text-center">Описание</th>
        <th class="border-2 border-slate-300 p-5 text-white text-center">Цена</th>
        <th class="border-2 border-slate-300 p-5 text-white text-center">Изменение</th>
    </tr>
    <tbody>
    @foreach ($ingredient as $Ingredient)
        <tr class="items-center">
            <td class="border-2 border-slate-300 p-5 text-white text-center">{{ $Ingredient->name }}</td>
            <td class="border-2 border-slate-300 p-5 text-white text-center">{{ $Ingredient->description }}</td>
            <td class="border-2 border-slate-300 p-5 text-white text-center">{{ $Ingredient->price }}</td>
            <td class="border-2 border-slate-300 p-5 text-white text-center">
                <a href=""class="text-blue-500 underline">Изменить</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="mt-4">
    <a href="" class="text-green-500 underline">Добавить новый блок</a>
</div>
            </div>
        </div>
    </div>
</x-app-layout>
