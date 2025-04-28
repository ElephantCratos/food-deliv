<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your orders') }}
        </h2>
    </x-slot>
    
    <div class="flex justify-center items-center mt-4">
        <div class="menu flex justify-center my-8">
            <div class="menu__frm1 flex flex-wrap justify-center gap-2 w-full">
                <a href="{{ route('All_Orders', App\Enums\OrderStatus::IN_PROGRESS->value) }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">In Progress</p>
                </a>
                <a href="{{ route('All_Orders', App\Enums\OrderStatus::IN_KITCHEN->value) }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">In Kitchen</p>
                </a>
                <a href="{{ route('All_Orders', App\Enums\OrderStatus::WAITING_FOR_COURIER->value) }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">Waiting for Courier</p>
                </a>
                <a href="{{ route('All_Orders', App\Enums\OrderStatus::COURIER_ON_THE_WAY->value) }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">Given to Courier</p>
                </a>
                <a href="{{ route('All_Orders', App\Enums\OrderStatus::COMPLETED->value) }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">Completed</p>
                </a>
                <a href="{{ route('All_Orders', App\Enums\OrderStatus::DECLINED->value) }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">Declined</p>
                </a>
                <a href="{{ route('All_Orders') }}" 
                   class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-1">
                    <p class="textOff">All</p>
                </a>
            </div>
        </div>
    </div>
    
    <section>
        @foreach ($Order as $order)
        <div class="flex justify-center p-4">
            <div class="order-block bg-white border border-gray-200 rounded-lg p-4 shadow-md" style="width: 70%;">
                <h3 class="text-lg font-semibold text-gray-800">Order #{{ $order->id }}</h3>
                <p class="text-gray-600">
                    Status: 
                    <span class="font-medium">
                        {{ $order->status->label() }}
                    </span>
                </p>

                <ul class="list-disc list-inside text-gray-600 mt-2">
                    @foreach($order->positions as $position)
                    <li>
                        {{ $position->dish->name }} - ${{ $position->price }} 
                        (Quantity: {{ $position->quantity }})
                    </li>
                    @endforeach
                </ul>
                
                <div class="mt-3">
                    <p class="text-gray-800"><span class="font-semibold">Address:</span> {{ $order->address }}</p>
                    <p class="text-gray-800"><span class="font-semibold">Expected at:</span> 
                        {{ $order->expected_at ?? 'As soon as possible' }}
                    </p>
                    @if($order->comment)
                    <p class="text-gray-800"><span class="font-semibold">Comment:</span> {{ $order->comment }}</p>
                    @endif
                    <p class="text-gray-800 font-semibold mt-2">Total Price: ${{ number_format($order->price, 2) }}</p>
                </div>

                @if($order->status === App\Enums\OrderStatus::IN_PROGRESS)
                <div class="flex justify-between mt-4">
                    <form action="{{ route('orders.accept', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Accept
                        </button>
                    </form>
                    <form action="{{ route('orders.decline', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Decline
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </section>
</x-app-layout>