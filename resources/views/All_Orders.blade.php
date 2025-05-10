<x-app-layout>
    <div class="pt-24 pb-4">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-8 text-center">
            {{ __('Все заказы') }}
        </h2>
    </div>
    
    <div class="flex justify-center items-center mt-4">
        <div class="menu flex justify-center my-8">
            <div class="menu__frm1 flex flex-wrap justify-center gap-2 w-full">
                <a href="{{ route('All_Orders', App\Enums\OrderStatus::IN_PROGRESS->value) }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">В процессе</p>
                </a>
                <a href="{{ route('All_Orders', App\Enums\OrderStatus::IN_KITCHEN->value) }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">На кухне</p>
                </a>
                <a href="{{ route('All_Orders', App\Enums\OrderStatus::WAITING_FOR_COURIER->value) }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">Ожидает курьера</p>
                </a>
                <a href="{{ route('All_Orders', App\Enums\OrderStatus::COURIER_ON_THE_WAY->value) }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">Передано курьеру</p>
                </a>
                <a href="{{ route('All_Orders', App\Enums\OrderStatus::COMPLETED->value) }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">Выполнено</p>
                </a>
                <a href="{{ route('All_Orders', App\Enums\OrderStatus::DECLINED->value) }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">Отклонено</p>
                </a>
                <a href="{{ route('All_Orders') }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">Все</p>
                </a>
            </div>
        </div>
    </div>
    
    <section>
        @foreach ($Order as $order)
        <div class="flex justify-center p-4">
            <div class="order-block bg-white border border-gray-200 rounded-lg p-4 shadow-md" style="width: 70%;">
                <h3 class="text-lg font-semibold text-gray-800">Заказ #{{ $order->id }}</h3>
                <p class="text-gray-600">
                    Статус: 
                    <span class="font-medium">
                        {{ $order->status->toRussian() }}
                    </span>
                </p>

                @if($order->promocode)
                <div class="mt-2 mb-2">
                    <p class="text-gray-800">
                        <span class="font-semibold">Промокод:</span> 
                        <span class="text-blue-600">{{ $order->promocode }}</span>
                    </p>
                    @if($order->discount_amount)
                    <p class="text-gray-800">
                        <span class="font-semibold">Тип скидки:</span> 
                        {{ $order->promocode_type === 'percent' ? 'Процентная' : 'Фиксированная' }}
                    </p>
                    <p class="text-gray-800">
                        <span class="font-semibold">Размер скидки:</span> 
                        @if($order->promocode_type === 'percent')
                            {{ $order->promocode_discount }}%
                        @else
                            {{ number_format($order->promocode_discount, 2) }}₽
                        @endif
                    </p>
                    <p class="text-gray-800">
                        <span class="font-semibold">Сумма скидки:</span> 
                        <span class="text-green-600">-{{ number_format($order->discount_amount, 2) }}₽</span>
                    </p>
                    @endif
                </div>
                @endif

                <div class="mt-3">
                    <p class="text-gray-800"><span class="font-semibold">Клиент:</span> {{ $order->customer->name ?? 'Не указан' }}</p>
                    <p class="text-gray-800"><span class="font-semibold">Телефон:</span> {{ $order->customer->phone ?? 'Не указан' }}</p>
                    <p class="text-gray-800"><span class="font-semibold">Адрес:</span> {{ $order->address }}</p>
                    <p class="text-gray-800"><span class="font-semibold">Доставить к:</span> 
                        {{ $order->expected_at ?? 'Как можно скорее' }}
                    </p>
                    @if($order->comment)
                    <p class="text-gray-800"><span class="font-semibold">Комментарий к заказу:</span> {{ $order->comment }}</p>
                    @endif
                </div>

                <ul class="list-disc list-inside text-gray-600 mt-2">
                    @foreach($order->positions as $position)
                    <li>
                        {{ $position->dish->name }} - {{ $position->price }}₽
                        (Количество: {{ $position->quantity }})
                    </li>
                    @endforeach
                </ul>
                
                <div class="mt-4">
                    <p class="text-gray-800 font-semibold text-lg">
                        <span>Итого к оплате:</span> 
                        <span class="text-green-600">{{ number_format($order->price, 2) }}₽</span>
                    </p>
                </div>

                @if($order->status === App\Enums\OrderStatus::IN_PROGRESS)
                <div class="flex justify-between mt-4">
                    <form action="{{ route('orders.accept', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Принять
                        </button>
                    </form>
                    <form action="{{ route('orders.decline', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Отклонить
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </section>
</x-app-layout>