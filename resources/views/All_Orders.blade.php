<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your orders') }}
        </h2>
    </x-slot>
    <div class="menu">
    <div class="menu__frm1">
        <a href="{{ route('All_Orders', 1) }}" style="text-decoration: none;"><p class="textOff">in progress</p></a>
        <a href="{{ route('All_Orders', 2) }}" style="text-decoration: none;"><p class="textOff">awaiting acceptance into the kitchen</p></a>
        <a href="{{ route('All_Orders', 3) }}" style="text-decoration: none;"><p class="textOff">in the kitchen</p></a>
        <a href="{{ route('All_Orders', 4) }}" style="text-decoration: none;"><p class="textOff">Waiting for the courier</p></a>
        <a href="{{ route('All_Orders', 5) }}" style="text-decoration: none;"><p class="textOff">The courier is on the way</p></a>
        <a href="{{ route('All_Orders', 6) }}" style="text-decoration: none;"><p class="textOff">completed</p></a>
        <a href="{{ route('All_Orders') }}" style="text-decoration: none;"><p class="textOff">All</p></a>
    </div>
</div>

    <section>
    @foreach ($Order as $order)
    <div class="flex justify-center p-4">

    <div class="order-block bg-white border border-gray-200 rounded-lg p-4 shadow-md" style="width: 70%;">
    <h3 class="text-lg font-semibold text-gray-800">Order #12345</h3>
    <p class="text-gray-600">Status: In Progress</p>

    <ul class="list-disc list-inside text-gray-600">
        <li>Pizza Margherita - $10.99</li>
        <li>Caesar Salad - $7.99</li>
        <li>Garlic Bread - $4.99</li>
    </ul>
    <p class="text-gray-800 font-semibold">Total Price: {{ $order->price }}</p>
    </div>
    </div>
    @endforeach
</section>
</x-app-layout>
