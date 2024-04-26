<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orders for Delivery') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-2 text-black">Orders</h3>
                    @if ($orders->isEmpty())
                        <p class="text-black">No orders for delivery.</p>
                    @else
                        <ul>
                            @foreach ($orders as $order)
                                <li class="text-black">
                                    <h4 class="font-semibold">Order #{{ $order->id }}</h4>
                                    <p>Status: {{ $order->status->name }}</p>
                                    <!-- Add more details as needed -->
                                @foreach($order->positions as $position)
                                    <li>{{$position->dish->name}} - ${{$position->price}} - @foreach ($position->ingredients as $pos) {{$pos->name}} @endforeach</li>
                                @endforeach
                                @if($order->courier_id == auth()->user()->id && $order->status->id == 6)
                                    <form action="{{ route('courier.delivered', ['id' => $order->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Delivered</button>
                                    </form>
                                @endif

                                @if($order->courier_id == auth()->user()->id && $order->status->id == 5)
                                    <form action="{{ route('courier.confirm', ['id' => $order->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Order picked up</button>
                                    </form>
                                @endif

                                @if($order->courier_id == null)

                                    <form action="{{ route('courier.accept-order', ['id' => $order->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Accept Order</button>
                                    </form>
                                    @endif

                                    </li>
                                    @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
