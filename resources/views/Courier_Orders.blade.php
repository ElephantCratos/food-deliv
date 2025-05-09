<x-app-layout>
    <div class="pt-24 pb-12">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-8">
            {{ __('Заказы курьера') }}
        </h2>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($orders->isEmpty())
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-4">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <p class="text-black">Нет заказов для курьера на данный момент</p>
                    </div>
                </div>
            @else
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <p class="font-semibold">Курьер: {{ $courier->name }}</p>
                    <p>Назначено заказов: {{ $orders->count() }}</p>
                </div>

                @foreach ($orders as $order)
                <div class="flex justify-center p-4">
                    <div class="order-block bg-white border border-gray-200 rounded-lg p-4 shadow-md" style="width: 70%;">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold">Заказ #{{ $order->id }}</h3>
                                <p class="text-gray-600">
                                    Статус: <span class="font-semibold">
                                        {{ $order->status->toRussian() }}
                                    </span>
                                </p>
                            </div>
                            <p class="text-gray-500">
                                {{ $order->created_at->format('d.m.Y H:i') }}
                            </p>
                        </div>

                        <div class="mt-4">
                            <h4 class="font-semibold mb-2">Состав заказа:</h4>
                            <ul class="divide-y divide-gray-200">
                                @foreach($order->positions as $position)
                                <li class="py-2">
                                    <div class="flex justify-between">
                                        <div>
                                            <span class="font-medium">{{ $position->dish->name }}</span>
                                            <span class="text-gray-500 text-sm ml-2">x{{ $position->quantity }}</span>
                                        </div>
                                        <span>{{ number_format($position->price * $position->quantity, 2) }}₽</span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-800"><span class="font-semibold">Адрес:</span> {{ $order->address }}</p>
                                    <p class="text-gray-800"><span class="font-semibold">Доставить к:</span> 
                                        {{ $order->expected_at_formatted }}
                                    </p>
                                    @if($order->comment)
                                    <p class="text-gray-800"><span class="font-semibold">Комментарий:</span> {{ $order->comment }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Всего позиций: {{ $order->positions->sum('quantity') }}</p>
                                    <p class="text-lg font-bold">Сумма: {{ number_format($order->price, 2) }}₽</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end space-x-3">
                        @if($order->status == App\Enums\OrderStatus::WAITING_FOR_COURIER)
                            <form action="{{ route('courier.confirm', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                                        aria-label="Confirm pickup for order #{{ $order->id }}">
                                    Заказ взят
                                </button>
                            </form>
                            @endif
                            @if($order->status == App\Enums\OrderStatus::COURIER_ON_THE_WAY)
                            <form action="{{ route('courier.delivered', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
                                        aria-label="Mark order #{{ $order->id }} as delivered">
                                    Заказ доставлен
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
