<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your orders') }}
        </h2>
    </x-slot>

    <ul>
        @foreach ($Orders as $order)
        <div class="flex justify-center p-4">

            <div class="order-block bg-white border border-gray-200 rounded-lg p-4 shadow-md" style="width: 70%;">
                <li class="text-black">
                    <h4 class="font-semibold">Order #{{ $order->id }}</h4>
                    <p>Status: {{ $order->status->name }}</p>
                    <!-- Add more details as needed -->
                    @foreach($order->positions as $position)
                <li>{{$position->dish->name}} - ${{$position->price}}</li>
                <li>Количество: {{ $position->quantity }}</li>
                @endforeach
                <p class="text-gray-800 font-semibold">Address: {{ $order->address }}</p>
                <p class="text-gray-800 font-semibold">Expected at: {{ $order->expected_at }}</p>
                <p class="text-gray-800 font-semibold">Comment: {{ $order->comment }}</p>
                <p class="text-gray-800 font-semibold">Total Price: {{ $order->price }}</p>
                @if($order->status_id == 1)
                <form action="{{ route('declineByCustomer', ['id' => $order->id]) }}" method="POST">
                    @csrf
                    @method('POST')
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-black font-bold py-2 px-4 rounded">Decline</button>
                </form>
                @endif
            </div>
        </div>
        @endforeach

</x-app-layout>
