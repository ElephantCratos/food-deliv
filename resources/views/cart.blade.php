@extends('layouts.baseLayout')

@section('head')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

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

@section('title', config('app.name', 'Laravel'))

@section('content')
<div class="container mx-auto mt-10 mb-20 px-4">
    <h1 class="text-3xl font-bold mb-6">Оформление заказа</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Левый блок: форма -->
        <div class="md:col-span-2 space-y-6">
            <form action="{{ route('send_order') }}" method="POST" class="space-y-4" id="orderForm">
                @csrf

                <!-- самовывоз / доставка -->
                <div class="flex items-center space-x-4">
                    <span class="font-medium">Доставка</span>
                    <div id="pickupToggle" class="toggle"></div>
                    <span class="font-medium">Самовызов</span>
                </div>

                <!-- адрес -->
                <div id="adressField" class="@error('adress') border-red-500 @enderror">
                    <label for="adressInput" class="block font-medium mb-1">Адрес доставки</label>
                    <input type="text" name="adress" id="adressInput"
                           class="w-full p-2 border border-gray-300 rounded-lg @error('adress') border-red-500 @enderror"
                           value="{{ old('adress') }}" required>
                    @error('adress')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- инфо самовывоза -->
                <div id="pickupInfo" class="hidden p-4 bg-gray-100 rounded-lg">
                    <p class="text-sm text-gray-700">Забирайте свой сочный шашлычок здесь:</p>
                    <p class="font-bold mt-1">Ул. Ленина д.3, 0 этаж</p>
                </div>

                <!-- hidden fast_value -->
                <input type="hidden" name="fast_value" id="fast_value" value="{{ old('fast_value','1') }}">

                <!-- выбор времени -->
                <div>
                    <label for="time" class="block font-medium mb-1">Время доставки</label>
                    <select name="time" id="time" class="w-full p-2 border border-gray-300 rounded-lg @error('time') border-red-500 @enderror">
                        <option value="null" {{ old('time','null') === 'null' ? 'selected' : '' }}>Не выбрано</option>
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot }}" {{ old('time') === $slot ? 'selected' : '' }}>{{ $slot }}</option>
                        @endforeach
                    </select>
                    @error('time')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- комментарий -->
                <div>
                    <label for="comment" class="block font-medium mb-1">Комментарий к заказу</label>
                    <textarea name="comment" id="comment" class="w-full p-2 border border-gray-300 rounded-lg @error('comment') border-red-500 @enderror" rows="3">{{ old('comment') }}</textarea>
                    @error('comment')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- промокод -->
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
                            <button type="button" id="removePromocode" class="text-red-500 hover:text-red-700 font-medium text-sm">Удалить</button>
                        </div>
                    @else
                        <div>
                            <label class="block font-medium mb-1">Промокод</label>
                            <div class="flex space-x-2">
                                <input type="text" id="promocodeInput" class="flex-1 p-2 border border-gray-300 rounded-lg" placeholder="Введите промокод" value="{{ old('promocode') }}">
                                <button type="button" onclick="applyPromocode()" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">Применить</button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- способ оплаты -->
                <div>
                    <label for="payment" class="block font-medium mb-1">Способ оплаты</label>
                    <select name="payment" id="payment" class="w-full p-2 border border-gray-300 rounded-lg @error('payment') border-red-500 @enderror">
                        <option value="card_online" {{ old('payment')==='card_online'?'selected':'' }}>Картой на сайте</option>
                        <option value="card_courier" {{ old('payment')==='card_courier'?'selected':'' }}>Картой курьеру</option>
                        <option value="cash" {{ old('payment')==='cash'?'selected':'' }}>Наличными</option>
                    </select>
                    @error('payment')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- кнопка отправки -->
                <div class="mt-6 flex justify-between">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg">Оформить заказ</button>
                </div>
            </form>
        </div>

        <!-- Правый блок: состав заказа -->
        <div class="bg-white p-6 rounded-lg shadow-md space-y-4">
            <h2 class="text-xl font-bold">Состав заказа</h2>
            <div id="cartItems" class="space-y-2">
                @forelse($positions as $pos)
                    <div class="flex justify-between items-center border-b pb-2 cart-item" data-id="{{ $pos->dish->id }}">
                        <div class="flex-1">
                            <span class="font-medium">{{ $pos->dish->name }}</span>
                            <span class="text-gray-500 text-sm" id="qty-{{ $pos->dish->id }}">x{{ $pos->quantity }}</span>
                        </div>
                        <div class="flex space-x-1 mx-4">
                            <button type="button" class="quantity-btn w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded text-sm font-bold" data-id="{{ $pos->dish->id }}" data-action="increase">+</button>
                            <button type="button" class="quantity-btn w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded text-sm font-bold" data-id="{{ $pos->dish->id }}" data-action="decrease">−</button>
                        </div>
                        <div class="w-16 text-right font-medium whitespace-nowrap item-total" id="total-{{ $pos->dish->id }}">{{ $pos->price * $pos->quantity }} ₽</div>
                    </div>
                @empty
                    <p class="text-gray-500">Ваша корзина пуста.</p>
                @endforelse

                @if(count($positions) > 0)
                    <div class="pt-2 flex justify-between items-center font-bold text-lg">
                        <span>Итого:</span>
                        <span id="cart-total">{{ $totalWithDiscount }} ₽</span>
                    </div>
                    <div id="summary-discount" class="text-sm text-green-600 mt-1" style="{{ $discountAmount>0?'':'display:none' }}">
                        Скидка по промокоду: −{{ $discountAmount }} ₽
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('orderForm');

    const toggle = document.getElementById('pickupToggle');
    const addressField = document.getElementById('adressField');
    const pickupInfo = document.getElementById('pickupInfo');
    const adressInput = document.getElementById('adressInput');
    const timeSelect = document.getElementById('time');
    const fastValue = document.getElementById('fast_value');
    const commentInput = document.getElementById('comment');
    const paymentSelect = document.getElementById('payment');

    const LS_KEYS = {
        pickup:   'order_pickup',
        adress:   'order_adress',
        time:     'order_time',
        comment:  'order_comment',
        payment:  'order_payment'
    };

    function restore() {
        const isPickup = localStorage.getItem(LS_KEYS.pickup) === 'true';
        if (isPickup) {
            toggle.classList.remove('active');
            addressField.classList.remove('hidden');
            pickupInfo.classList.add('hidden');
            adressInput.readOnly = false;
            adressInput.value = localStorage.getItem(LS_KEYS.adress) || '{{ old('adress') }}' || '';
        } else {
            toggle.classList.add('active');
            addressField.classList.add('hidden');
            pickupInfo.classList.remove('hidden');
            adressInput.readOnly = true;
            adressInput.value = 'ул. Пушкина, д. Колотушкина';
        }

        timeSelect.value    = localStorage.getItem(LS_KEYS.time)    || timeSelect.value;
        commentInput.value  = localStorage.getItem(LS_KEYS.comment) || '{{ old('comment') }}';
        paymentSelect.value = localStorage.getItem(LS_KEYS.payment) || paymentSelect.value;

        fastValue.value = timeSelect.value === 'null' ? '1' : '0';
    }

    function bindSave(el, key) {
        el.addEventListener('change', () => {
            localStorage.setItem(key, el.value);
        });
    }
    bindSave(adressInput,  LS_KEYS.adress);
    bindSave(timeSelect,   LS_KEYS.time);
    bindSave(commentInput, LS_KEYS.comment);
    bindSave(paymentSelect, LS_KEYS.payment);

    toggle.addEventListener('click', () => {
        const isPickupNow = !toggle.classList.toggle('active');

        if (isPickupNow) {
            // Самовывоз — адрес вводится вручную
            addressField.classList.remove('hidden');
            pickupInfo.classList.add('hidden');
            adressInput.readOnly = false;
            adressInput.value = localStorage.getItem(LS_KEYS.adress) || '';
        } else {
            // Доставка — адрес фиксированный и readonly
            addressField.classList.add('hidden');
            pickupInfo.classList.remove('hidden');
            adressInput.readOnly = true;
            adressInput.value = 'Самовывоз';
            localStorage.setItem(LS_KEYS.adress, adressInput.value);
        }

        localStorage.setItem(LS_KEYS.pickup, isPickupNow.toString());
    });

    restore();

    timeSelect.addEventListener('change', () => {
        fastValue.value = timeSelect.value === 'null' ? '1' : '0';
        localStorage.setItem(LS_KEYS.time, timeSelect.value);
    });

    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            const id = btn.dataset.id;
            const action = btn.dataset.action;
            const qtyEl = document.getElementById(`qty-${id}`);
            let qty = parseInt(qtyEl.textContent.replace('x', ''), 10);
            qty = action === 'increase' ? qty + 1 : Math.max(0, qty - 1);

            fetch(`/cart/update/${id}`, {
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
                    document.querySelector(`.cart-item[data-id="${id}"]`).remove();
                } else {
                    qtyEl.textContent = 'x' + qty;
                    document.getElementById(`total-${id}`).textContent = data.itemTotal + ' ₽';
                }
                document.getElementById('cart-total').textContent = data.totalWithDiscount + ' ₽';
                const summaryDisc = document.getElementById('summary-discount');
                if (data.discountAmount > 0) {
                    summaryDisc.style.display = 'block';
                    summaryDisc.textContent = 'Скидка по промокоду: −' + data.discountAmount + ' ₽';
                } else {
                    summaryDisc.style.display = 'none';
                }
                const promoDisc = document.querySelector('#promoSection .text-green-600:last-child');
                if (promoDisc && data.discountAmount !== undefined) {
                    promoDisc.textContent = 'Скидка: ' + data.discountAmount + '₽';
                }
            });
        });
    });

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

    form.addEventListener('submit', () => {
        Object.values(LS_KEYS).forEach(k => localStorage.removeItem(k));
    });
});


</script>



@endsection

@section('footer')
<footer class="bg-gray-100 text-gray-800 py-8 mt-10 border-t">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between gap-12">

            <!-- О НАС -->
            <div id="about" class="md:w-1/2 space-y-4">
                <h2 class="text-lg font-semibold">О нас</h2>
                <p>
                    <strong>«Шашлычный двор»</strong> – это сочное мясо, приготовленное на мангале по традиционным армянским рецептам. Мы используем только свежие продукты, фирменные маринады и проверенные временем техники приготовления.
                </p>
                <p>
                    Приглашаем насладиться вкусом настоящего шашлыка в уютной атмосфере или закажите с доставкой — мы работаем, чтобы вы ели вкусно!
                </p>
            </div>

            <!-- КОНТАКТЫ -->
            <div id="contacts" class="md:w-1/2 space-y-4">
                <h2 class="text-lg font-semibold">Контакты</h2>
                <ul class="space-y-1 text-sm">
                    <li>📍 <strong>График:</strong> Вторник – Воскресенье, с 11:00 до 23:00</li>
                    <li>📞 <strong>Телефон для заказа:</strong> <a href="tel:+79090353545" class="text-blue-600 hover:underline">+7 909 035 35 45</a></li>
                    <li>🗺️ <strong>Яндекс.Карты:</strong> 
                        <a href="https://yandex.ru/maps/org/shashlychny_dvor/81098053171/?ll=65.435334%2C62.140169" 
                           target="_blank" class="text-blue-600 hover:underline">
                            Перейти
                        </a>
                    </li>
                    <li>📍 <strong>2ГИС:</strong> 
                        <a href="https://2gis.ru/nyagan/firm/70000001094977366" 
                           target="_blank" class="text-blue-600 hover:underline">
                            Посмотреть
                        </a>
                    </li>
                    <li>💬 <strong>ВКонтакте:</strong> 
                        <a href="https://vk.com/shashliknya?from=groups" 
                           target="_blank" class="text-blue-600 hover:underline">
                            vk.com/shashliknya
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- НИЖНЯЯ СТРОКА -->
        <div class="mt-8 border-t pt-4 text-xs text-gray-500 text-center">
            &copy; {{ date('Y') }} Шашлычный двор. Все права защищены.
        </div>

</footer>
@endsection