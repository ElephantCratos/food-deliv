@extends('layouts.baseLayout')

@section('head')
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title')
{{ config('app.name', 'Laravel') }}
@endsection
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
@endsection

@section('nav')
@parent
@endsection
@section('content')
<div class="container mx-auto mt-10 mb-10">
    <h1 class="text-2xl font-bold mb-4">Корзина</h1>

    <!-- Блок промокода -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        @if($promocode)
        <div class="flex justify-between items-center bg-green-50 p-3 rounded-md mb-3">
            <div>
                <span class="font-medium">Промокод:</span> 
                <span class="text-green-600">{{ $promocode->code }}</span>
                <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                    {{ $promocode->type === 'percent' ? $promocode->discount.'%' : $promocode->discount.'₽' }}
                </span>
                <span class="ml-2 text-green-600">Скидка: {{ $discountAmount }}₽</span>
            </div>
            <form action="{{ route('cart.remove-promocode') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">
                    Удалить
                </button>
            </form>
        </div>
        @else
        <form action="{{ route('cart.apply-promocode') }}" method="POST" class="flex">
            @csrf
            <input type="text" name="promocode" 
                   class="flex-grow px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-yellow-500" 
                   placeholder="Введите промокод" required>
            <button type="submit" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-r-md">
                Применить
            </button>
        </form>
        @endif
    </div>

    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto">
            <div class="py-2 align-middle inline-block min-w-full">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <!-- ... существующая таблица с товарами ... -->
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Название
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Количество
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Цена
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Топинги
                                </th>
                                <th scope="col" class="relative px-6 py-3 "></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($positions != null)
                            @forelse ($positions as $position)
                            <tr class="bg-white">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{$position->dish->name}}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"></div>
                                    {{$position->quantity}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"></div>
                                    {{$position->price * $position->quantity}}₽
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"></div>
                                    @foreach ($position->ingredients as $ingredient)
                                    <p>{{ $ingredient->name }}</p>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{route('delete-order-position',$position->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-white bg-red-500 hover:bg-red-700 font-bold py-2 px-3 rounded" type="submit">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Ваша корзина пуста
                                </td>
                            </tr>
                            @endforelse
                            @else
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Ваша корзина пуста
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 flex justify-center">
            <form action="{{route('send_order')}}" method="POST">
                @csrf
                <table>
                    <tr>
                        <td><label for="adress">Адрес</label></td>
                        <td><input type="text" name="adress" class="border rounded px-2 py-1"></td>
                    </tr>
                    <tr>
                        <td><label for="fast">Как можно скорее</label></td>
                        <td>
                            <input type="checkbox" name="fast" id="fast">
                            <input type="hidden" name="fast_value" id="fast_value" value="0">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="time">Время доставки</label></td>
                        <td><input type="time" name="time" value="{{ now()->addHour()->format('H:i') }}" min="{{ now()->addHour()->format('H:i') }}" class="border rounded px-2 py-1"></td>
                    </tr>
                    <tr>
                        <td><label for="comment">Комментарии к заказу</label></td>
                        <td><textarea name="comment" class="border rounded px-2 py-1"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium">Сумма заказа:</span>
                                <span>{{ $lastOrder ? $lastOrder->price + $discountAmount : 0 }}₽</span>
                            </div>
                            @if($promocode)
                            <div class="flex justify-between items-center mb-2 text-green-600">
                                <span class="font-medium">Скидка:</span>
                                <span>-{{ $discountAmount }}₽</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center font-bold text-lg border-t pt-2">
                                <span>Итого к оплате:</span>
                                <span>{{ $lastOrder ? $totalWithDiscount : 0 }}₽</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pt-4">
                            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 w-full rounded" type="submit">
                                Оформить заказ
                            </button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
    const checkbox = document.getElementById('fast');
    const hiddenInput = document.getElementById('fast_value');

    hiddenInput.value = checkbox.checked ? '1' : '0';

    checkbox.addEventListener('change', function() {
        hiddenInput.value = this.checked ? '1' : '0';
    });
</script>
@endsection

@section('footer')
2024 Food Delivery Catalog. All rights reserved.
@endsection