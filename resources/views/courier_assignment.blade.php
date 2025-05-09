<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Courier Assignment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($orders->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <p>Нет заказов для назначения курьеру.</p>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="font-semibold text-lg mb-4">Доступные курьеры</h3>
                        <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($couriers as $courier)
                            <li class="p-4 border rounded-lg">
                                <p class="font-medium">{{ $courier->name }}</p>
                                <p class="text-sm text-gray-600">{{ $courier->email }}</p>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                @foreach($orders as $order)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-lg">Заказ #{{ $order->id }}</h3>
                                <p class="text-gray-600">Статус: {{ $order->status->toRussian()}}</p>
                            </div>
                            <span class="text-sm text-gray-500">
                                {{ $order->created_at->format('d.m.Y H:i') }}
                            </span>
                        </div>

                        <div class="mt-4">
                            <h4 class="font-semibold mb-2">Состав заказа:</h4>
                            <ul class="divide-y divide-gray-200">
                                @foreach($order->positions as $position)
                                <li class="py-2 flex justify-between">
                                    <span>{{ $position->dish->name }} x{{ $position->quantity }}</span>
                                    <span>{{ number_format($position->price * $position->quantity, 2) }}₽</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p>Адрес: {{ $order->address }}</p>
                                <p>Доставить к: {{ $order->expected_at ?? 'ASAP' }}</p>
                                <p>Курьер: {{ $order->courier->name}}</p>
                            </div>
                            
                            <div>
                                @if($order->courier)
                                <p class="font-semibold">Назначен в данный момент:</p>
                                <p>{{ $order->courier->name }}</p>
                                @endif

                                <form action="{{ route('courier.assign', $order->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <div class="flex gap-2">
                                        <select name="courier_id" class="rounded-md border-gray-300 shadow-sm">
                                            <option value="">Выбрать курьера</option>
                                            @foreach($couriers as $courier)
                                            <option value="{{ $courier->id }}" {{ $order->courier_id == $courier->id ? 'selected' : '' }}>
                                                {{ $courier->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" 
                                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                                                aria-label="Assign courier to order #{{ $order->id }}">
                                            Назначить
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>