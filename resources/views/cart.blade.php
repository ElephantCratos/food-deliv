@extends('layouts.baseLayout')

@section('head')
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title')
{{ config('app.name', 'Laravel') }}
@endsection
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
<style>
    .toggle {
        position: relative;
        width: 60px;
        height: 30px;
        background: #e2e8f0;
        border-radius: 9999px;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    .toggle::before {
        content: '';
        position: absolute;
        width: 24px;
        height: 24px;
        top: 3px;
        left: 3px;
        background: white;
        border-radius: 50%;
        transition: left 0.3s ease;
    }
    .toggle.active {
        background: #48bb78;
    }
    .toggle.active::before {
        left: 33px;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto mt-10 mb-20 px-4">
    <h1 class="text-3xl font-bold mb-6">Оформление заказа</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Левая часть: форма доставки -->
        <div class="md:col-span-2 space-y-6">
            <!-- Промокод: отдельная форма -->
            <div class="mt-6 mb-6">
                <form id="promoForm" action="{{ route('cart.apply-promocode') }}" method="POST" class="flex space-x-2">
                    @csrf
                    <input type="text" 
                           name="promocode" 
                           id="promocodeInput"
                           class="flex-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Введите промокод" 
                           required>
                    <button type="submit" 
                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">
                        Применить
                    </button>
                </form>
                <div id="promoMessage" class="text-green-600"></div>
            </div>

            <!-- Форма оформления заказа -->
            <form action="{{ route('send_order') }}" method="POST" class="space-y-4">
                @csrf

                <div class="flex items-center space-x-4">
                    <span class="font-medium">Самовывоз</span>
                    <div id="pickupToggle" class="toggle"></div>
                    <span class="font-medium">Доставка</span>
                </div>

                <div id="adressField">
                    <label class="block font-medium mb-1" for="adress">Адрес доставки</label>
                    <input type="text" name="adress" id="adressInput" class="w-full p-2 border border-gray-300 rounded-lg" required>
                </div>

                <div id="pickupInfo" class="hidden p-4 bg-gray-100 rounded-lg">
                    <p class="text-sm text-gray-700">Забирайте свой сочный шашлычок здесь, ЙОУ:</p>
                    <p class="font-bold mt-1">ул. Пушкина, д. Колотушкина</p>
                </div>

                <div>
                    <label class="block font-medium mb-1">Время доставки</label>
                    <div class="flex gap-2">
                        <input type="time" name="time" class="w-full p-2 border border-gray-300 rounded-lg" value="09:00" required>
                    </div>
                </div>

                <div>
                    <label class="block font-medium mb-1" for="comment">Комментарий к заказу</label>
                    <textarea name="comment" class="w-full p-2 border border-gray-300 rounded-lg"></textarea>
                </div>

                <div>
                    <label class="block font-medium mb-1">Способ оплаты</label>
                    <select name="payment" class="w-full p-2 border border-gray-300 rounded-lg">
                        <option value="card_online">Картой на сайте</option>
                        <option value="card_courier">Картой курьеру</option>
                        <option value="cash">Наличными</option>
                    </select>
                </div>

                <div class="mt-6 flex justify-between">
                    <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg" type="submit">
                        Оформить заказ
                    </button>
                </div>
            </form>
        </div>

        <!-- Правая часть: состав заказа -->
        <div class="bg-white p-6 rounded-lg shadow-md space-y-4">
            <h2 class="text-xl font-bold">Состав заказа</h2>
            @forelse ($positions as $position)
                <div class="flex justify-between">
                    <span>{{ $position->dish->name }} x{{ $position->quantity }}</span>
                    <span>{{ $position->price * $position->quantity }} ₽</span>
                </div>
            @empty
                <p class="text-gray-500">Ваша корзина пуста.</p>
            @endforelse

            <hr class="my-4">

            <div class="flex justify-between font-bold text-lg">
                <span>Итого:</span>
                <span>{{ $totalWithDiscount }} ₽</span>
            </div>
            @if($discountAmount > 0)
                <div class="text-sm text-green-600">
                    Скидка по промокоду: −{{ $discountAmount }} ₽
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    const toggle = document.getElementById('pickupToggle');
    const addressField = document.getElementById('adressField');
    const pickupInfo = document.getElementById('pickupInfo');
    const adressInput = document.getElementById('adressInput');

    toggle.addEventListener('click', () => {
        toggle.classList.toggle('active');
        const isPickup = toggle.classList.contains('active');

        if (isPickup) {
            addressField.classList.add('hidden');
            pickupInfo.classList.remove('hidden');
            adressInput.value = 'ул. Пушкина, д. Колотушкина';
        } else {
            addressField.classList.remove('hidden');
            pickupInfo.classList.add('hidden');
            adressInput.value = '';
        }
    });

    // AJAX запрос для отправки промокода
    const promoForm = document.getElementById('promoForm');
    promoForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const promocode = document.getElementById('promocodeInput').value;
        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch("{{ route('cart.apply-promocode') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': _token,
            },
            body: JSON.stringify({ promocode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('promoMessage').textContent = `Промокод применён! Скидка: ${data.discount}₽`;
                // Обновить стоимость на странице, если нужно
            } else {
                document.getElementById('promoMessage').textContent = 'Ошибка применения промокода.';
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>

@endsection

@section('footer')
2024 Food Delivery Catalog. All rights reserved.
@endsection
