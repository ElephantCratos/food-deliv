@extends('layouts.baseLayout')

@section('head')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('title', config('app.name', 'Laravel'))

@section('content')
<div class="container mx-auto mt-10 mb-20 px-4">
    <h1 class="text-3xl font-bold mb-6">Оформление заказа</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Левый блок: форма -->
        <div class="md:col-span-2 space-y-6">
            <form action="{{ route('send_order') }}" method="POST" class="space-y-4">
                @csrf

                <div class="flex items-center space-x-4">
                    <span class="font-medium">Самовывоз</span>
                    <div id="pickupToggle" class="toggle"></div>
                    <span class="font-medium">Доставка</span>
                </div>

                <div id="adressField">
                    <label for="adress" class="block font-medium mb-1">Адрес доставки</label>
                    <input type="text" name="adress" id="adressInput"
                           class="w-full p-2 border border-gray-300 rounded-lg" required>
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
                    <label for="comment" class="block font-medium mb-1">Комментарий к заказу</label>
                    <textarea name="comment" id="comment"
                              class="w-full p-2 border border-gray-300 rounded-lg"></textarea>
                </div>

                <div id="promoSection">
                    @if($promocode)
                        <div class="flex justify-between items-center p-3 rounded-lg mb-3 bg-gray-50">
                            <div class="flex items-center space-x-3">
                                <span class="font-medium">Промокод:</span>
                                <span class="text-green-600">{{ $promocode->code }}</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-sm rounded-lg">
                                    {{ $promocode->type === 'percent'
                                        ? $promocode->discount.'%'
                                        : $promocode->discount.'₽' }}
                                </span>
                                <span class="text-green-600">Скидка: {{ $discountAmount }}₽</span>
                            </div>
                            <button type="button" id="removePromocode"
                                    class="text-red-500 hover:text-red-700 font-medium text-sm">
                                Удалить
                            </button>
                        </div>
                    @else
                        <div>
                            <label class="block font-medium mb-1">Промокод</label>
                            <div class="flex space-x-2">
                                <input type="text" id="promocodeInput"
                                       class="flex-1 p-2 border border-gray-300 rounded-lg"
                                       placeholder="Введите промокод">
                                <button type="button" onclick="applyPromocode()"
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
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg">
                        Оформить заказ
                    </button>
                </div>
            </form>
        </div>

        <!-- Правый блок: состав заказа -->
        <div class="bg-white p-6 rounded-lg shadow-md space-y-4">
            <h2 class="text-xl font-bold">Состав заказа</h2>
            <div id="cartItems" class="space-y-2">
                @forelse ($positions as $position)
                    <div class="flex justify-between items-center border-b pb-2 cart-item"
                         data-id="{{ $position->dish->id }}">
                        <div class="flex-1">
                            <span class="font-medium">{{ $position->dish->name }}</span>
                            <span class="text-gray-500 text-sm">x{{ $position->quantity }}</span>
                        </div>
                        <div class="flex space-x-1 mx-4">
                            <button class="quantity-btn w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded text-sm font-bold"
                                    data-id="{{ $position->dish->id }}" data-action="increase">+</button>
                            <button class="quantity-btn w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded text-sm font-bold"
                                    data-id="{{ $position->dish->id }}" data-action="decrease">−</button>
                        </div>
                        <div class="w-16 text-right font-medium whitespace-nowrap item-total">
                            {{ $position->price * $position->quantity }} ₽
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Ваша корзина пуста.</p>
                @endforelse

                @if(count($positions) > 0)
                    <div class="pt-2 flex justify-between items-center font-bold text-lg">
                        <span>Итого:</span>
                        <span id="cart-total">{{ $totalWithDiscount }} ₽</span>
                    </div>
                    @if($discountAmount > 0)
                        <div class="promo-card text-sm text-green-600 mt-1" id="promoCard">
                            Скидка по промокоду: −{{ $discountAmount }} ₽
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Скрипты внизу, сразу после контента --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Toggle pickup/delivery
    const toggle = document.getElementById('pickupToggle');
    const addressField = document.getElementById('adressField');
    const pickupInfo = document.getElementById('pickupInfo');
    const adressInput = document.getElementById('adressInput');

    toggle.addEventListener('click', () => {
        toggle.classList.toggle('active');
        if (toggle.classList.contains('active')) {
            addressField.classList.add('hidden');
            pickupInfo.classList.remove('hidden');
            adressInput.value = 'ул. Пушкина, д. Колотушкина';
        } else {
            addressField.classList.remove('hidden');
            pickupInfo.classList.add('hidden');
            adressInput.value = '';
        }
    });

    // Quantity buttons
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const dishId = this.dataset.id;
            const action = this.dataset.action;
            const cartItem = this.closest('.cart-item');
            const quantitySpan = cartItem.querySelector('.text-gray-500');
            let qty = parseInt(quantitySpan.innerText.replace('x',''), 10);
            qty = action === 'increase' ? qty + 1 : Math.max(0, qty - 1);

            fetch(`/cart/update/${dishId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quantity: qty })
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;

                if (data.dishRemoved) {
                    cartItem.remove();
                    if (!document.querySelector('.cart-item')) {
                        document.getElementById('cartItems').innerHTML =
                            '<p class="text-gray-500">Ваша корзина пуста.</p>';
                    }
                } else {
                    quantitySpan.innerText = 'x' + qty;
                    cartItem.querySelector('.item-total').textContent =
                        data.itemTotal + ' ₽';
                }

                // Обновляем итоговую сумму
                document.getElementById('cart-total').textContent =
                    data.totalWithDiscount + ' ₽';

                // Обновляем текст скидки
                const promoText = document.querySelector(
                    '#promoSection .text-green-600:last-child'
                );
                const promoTextCard = document.getElementById('promoCard');
                if (promoText) {
                    promoText.textContent = 'Скидка: ' + data.discountAmount + '₽';
                    promoTextCard.textContent = 'Скидка по промокоду: -' + data.discountAmount + '₽';
                }

                // Счётчик в шапке
                const cartCountEl = document.querySelector('.cart-count');
                if (cartCountEl) cartCountEl.textContent = data.cartCount;
            });
        });
    });

    // Применение промокода
    window.applyPromocode = () => {
        const code = document.getElementById('promocodeInput').value.trim();
        if (!code) return alert('Введите промокод');
        fetch('{{ route('cart.apply-promocode') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ promocode: code })
        })
        .then(r => r.json())
        .then(d => d.success ? location.reload() : alert('Ошибка применения промокода'))
        .catch(() => alert('Ошибка соединения с сервером'));
    };

    // Удаление промокода
    document.getElementById('removePromocode')?.addEventListener('click', () => {
        fetch('{{ route('cart.remove-promocode') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(r => r.json())
        .then(d => d.success ? location.reload() : alert('Ошибка при удалении промокода'))
        .catch(() => alert('Ошибка соединения с сервером'));
    });
});
</script>
@endsection

@section('footer')
    2024 Food Delivery Catalog. All rights reserved.
@endsection
