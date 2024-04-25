<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
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
                                    @if($order->status_id == 2 )
                                    <form action="{{ route('kitchen.confirm', ['id' => $order->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Confirm Preparation</button>
                                    </form>
                                        @endif
                                    @if($order->status_id == 3)
                                    <form action="{{ route('kitchen.transfer', ['id' => $order->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Call a courier</button>
                                    </form>
                                    @endif

                                    @if($order->status_id == 4)
                                        <form action="{{ route('kitchen.courier-arrived', ['id' => $order->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Have transferred to the courier</button>
                                        </form>
                                    @endif

                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
</x-app-layout>
