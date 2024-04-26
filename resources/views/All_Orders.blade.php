<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your orders') }}
        </h2>
    </x-slot>
    <div class="flex justify-center items-center  mt-4">
        <div class="menu flex justify-center my-8">
            <div class="menu__frm1 flex justify-around w-full">
                <a href="{{ route('All_Orders', 1) }}" class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-4">
                    <p class="textOff">in progress</p>
                </a>
                <a href="{{ route('All_Orders', 2) }}" class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-4">
                    <p class="textOff">awaiting acceptance into the kitchen</p>
                </a>
                <a href="{{ route('All_Orders', 3) }}" class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-4">
                    <p class="textOff">in the kitchen</p>
                </a>
                <a href="{{ route('All_Orders', 4) }}" class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-4">
                    <p class="textOff">Waiting for the courier</p>
                </a>
                <a href="{{ route('All_Orders', 5) }}" class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-4">
                    <p class="textOff">The courier is on the way</p>
                </a>
                <a href="{{ route('All_Orders', 6) }}" class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-4">
                    <p class="textOff">completed</p>
                </a>
                <a href="{{ route('All_Orders', 7) }}" class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-4">
                    <p class="textOff">declined</p>
                </a>
                <a href="{{ route('All_Orders') }}" class="text-decoration-none bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mx-4">
                    <p class="textOff">All</p>
                </a>
            </div>
        </div>
    </div>
    <section>


        @foreach ($Order as $order)

                <div class="flex justify-center p-4">

                    <div class="order-block bg-white border border-gray-200 rounded-lg p-4 shadow-md" style="width: 70%;">
                        <h3 class="text-lg font-semibold text-gray-800">Order {{$order->id}}</h3>
                        <p class="text-gray-600">Status: {{$order->status->name}}</p>

                        <ul class="list-disc list-inside text-gray-600">
                            @foreach($order->positions as $position)
                            <li>{{$position->dish->name}} - ${{$position->price}} - @foreach ($position->ingredients as $pos) {{$pos->name}} @endforeach</li>
                            @endforeach

                        </ul>
                        <p class="text-gray-800 font-semibold">Total Price: {{ $order->price }}</p>
                        <div class="flex justify-between mt-4">

                            @if($order->status_id == 1)
                                <form action="{{ route('orders.accept', ['id' => $order->id]) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-black font-bold py-2 px-4 rounded">Accept</button>
                                </form>
                                <form action="{{ route('orders.decline', ['id' => $order->id]) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-black font-bold py-2 px-4 rounded">Decline</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
        @endforeach
    </section>
</x-app-layout>
