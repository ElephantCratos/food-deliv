<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orders That Need Preparation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($orders->isEmpty())
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-4">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <p class="text-black">No orders need preparation.</p>
                    </div>
                </div>
            @else
                @foreach ($orders as $order)
                <div class="flex justify-center p-4">
                    <div class="order-block bg-white border border-gray-200 rounded-lg p-4 shadow-md" style="width: 70%;">
                        <h3 class="text-lg font-semibold text-gray-800">Order #{{ $order->id }}</h3>
                        <p class="text-gray-600">Status: {{ $order->status->name }}</p>

                        <ul class="list-disc list-inside text-gray-600">
                            @foreach($order->positions as $position)
                            <li>{{ $position->dish->name }} - ${{ $position->price }}</li>
                            <li>Quantity: {{ $position->quantity }}</li>
                            @endforeach
                        </ul>

                        <div class="mt-2">
                            <p class="text-gray-800 font-semibold">Address: {{ $order->address }}</p>
                            <p class="text-gray-800 font-semibold">Expected at: {{ $order->expected_at ?? 'As soon as possible' }}</p>
                            @if($order->comment)
                            <p class="text-gray-800 font-semibold">Comment: {{ $order->comment }}</p>
                            @endif
                            <p class="text-gray-800 font-semibold mt-2">Total Price: ${{ number_format($order->price, 2) }}</p>
                        </div>

                        @if($order->status === App\Enums\OrderStatus::IN_KITCHEN)
                        <div class="flex justify-between mt-4">
                            <form action="{{ route('kitchen.ready', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Order Ready
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>