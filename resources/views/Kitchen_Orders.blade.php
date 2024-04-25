<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders That Need Preparation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-2 text-black">Orders</h3>
                    @if ($orders->isEmpty())
                        <p class="text-black">No orders need preparation.</p>
                    @else
                        <ul>
                            @foreach ($orders as $order)
                                <li class="text-black">
                                    <h4 class="font-semibold">Order #{{ $order->id }}</h4>
                                    <p>Status: {{ $order->status->name }}</p>
                                    <!-- Add more details as needed -->
                                    <form action="{{ route('kitchen.confirm', ['id' => $order->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Confirm Preparation</button>
                                    </form>
                                    <form action="{{ route('kitchen.transfer', ['id' => $order->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Transfer to Courier</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
