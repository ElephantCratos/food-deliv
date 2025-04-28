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
                    <p class="font-bold mt-1">Ул. Ленина д.3, 0 этаж</p>
                </div>

                <div>
                    <label class="block font-medium mb-1">Время доставки</label>
                    <div class="flex flex-wrap gap-2">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="time" value="09:00 - 09:30" class="form-radio" checked>
                            <span>09:00 - 09:30</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="time" value="09:30 - 10:00" class="form-radio">
                            <span>09:30 - 10:00</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="time" value="other" class="form-radio">
                            <span>Другое время</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block font-medium mb-1" for="comment">Комментарий к заказу</label>
                    <textarea name="comment" class="w-full p-2 border border-gray-300 rounded-lg"></textarea>
                </div>

                <div id="promoSection">
                    @if($promocode)
                        <div class="flex justify-between items-center p-3 rounded-lg mb-3 bg-gray-50">
                            <div class="flex items-center space-x-3">
                                <span class="font-medium">Промокод:</span>
                                <span class="text-green-600">{{ $promocode->code }}</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-sm rounded-lg">
                                    {{ $promocode->type === 'percent' ? $promocode->discount.'%' : $promocode->discount.'₽' }}
                                </span>
                                <span class="text-green-600">Скидка: {{ $discountAmount }}₽</span>
                            </div>
                            <button type="button" id="removePromocode" class="text-red-500 hover:text-red-700 font-medium text-sm">
                                Удалить
                            </button>
                        </div>
                    @else
                        <div>
                            <label class="block font-medium mb-1">Промокод</label>
                            <div class="flex space-x-2">
                                <input type="text" 
                                       id="promocodeInput" 
                                       class="flex-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                       placeholder="Введите промокод">
                                <button type="button" 
                                        onclick="applyPromocode()" 
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">
                                    Применить
                                </button>
                            </div>
                        </div>
                    @endif
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
                    <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg" type="submit">Оформить заказ</button>
                </div>
            </form>
        </div>

        <!-- Правая часть: состав заказа -->
        <div class="bg-white p-6 rounded-lg shadow-md space-y-4">
            <h2 class="text-xl font-bold">Состав заказа</h2>
            <div id="cartItems">
                @forelse ($positions as $position)
                    <div class="flex justify-between items-center cart-item" data-id="{{ $position->dish->id }}">
                        <div class="flex items-center space-x-3">
                            <span>{{ $position->dish->name }} x{{ $position->quantity }}</span>
                            <button 
                                class="quantity-btn bg-gray-200 hover:bg-gray-300 px-2 rounded" 
                                data-id="{{ $position->dish->id }}" 
                                data-action="increase">+</button>
                            <button 
                                class="quantity-btn bg-gray-200 hover:bg-gray-300 px-2 rounded" 
                                data-id="{{ $position->dish->id }}" 
                                data-action="decrease">−</button>
                        </div>
                        <span class="item-total">{{ $position->price * $position->quantity }} ₽</span>
                    </div>
                @empty
                    <p class="text-gray-500">Ваша корзина пуста.</p>
                @endforelse
            </div>

            <hr class="my-4">

            <div class="flex justify-between font-bold text-lg">
                <span>Итого:</span>
                <span id="cart-total">{{ $totalWithDiscount }} ₽</span>
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


    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const dishId = this.dataset.id;
            const action = this.dataset.action;
            const cartItem = this.closest('.cart-item');
            const quantitySpan = this.parentElement.querySelector('span');
            let currentQuantity = parseInt(quantitySpan.innerText.split('x')[1]);

            if (action === 'increase') {
                currentQuantity++;
            } else if (action === 'decrease') {
                currentQuantity = Math.max(0, currentQuantity - 1);
            }

            fetch(`/cart/update/${dishId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ quantity: currentQuantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.dishRemoved) {
                      
                        cartItem.remove();

                        if (document.querySelectorAll('.cart-item').length === 0) {
                            document.getElementById('cartItems').innerHTML = '<p class="text-gray-500">Ваша корзина пуста.</p>';
                        }
                    } else {
                        quantitySpan.innerText = quantitySpan.innerText.split('x')[0] + 'x' + currentQuantity;
                        cartItem.querySelector('.item-total').textContent = (data.itemTotal || 0) + ' ₽';
                    }
                    
                  
                    document.getElementById('cart-total').textContent = data.total + ' ₽';
                    
                    
                    const cartCountEl = document.querySelector('.cart-count');
                    if (cartCountEl) {
                        cartCountEl.textContent = data.cartCount || 0;
                    }
                }
            });
        });
    });

    function applyPromocode() {
        const promocode = document.getElementById('promocodeInput').value;
        if (!promocode) {
            alert('Введите промокод');
            return;
        }

        fetch('{{ route('cart.apply-promocode') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ promocode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Ошибка применения промокода');
            }
        })
        .catch(() => {
            alert('Ошибка соединения с сервером');
        });
    }

    document.getElementById('removePromocode')?.addEventListener('click', function () {
        fetch('{{ route('cart.remove-promocode') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Ошибка при удалении промокода');
            }
        })
        .catch(() => {
            alert('Ошибка соединения с сервером');
        });
    });
</script>
@endsection

@section('footer')
2024 Food Delivery Catalog. All rights reserved.
@endsection