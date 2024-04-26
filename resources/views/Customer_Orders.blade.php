<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your orders') }}
        </h2>
    </x-slot>

        <ul>
            @foreach ($Orders as $order)
                <li class="text-black">
                    <h4 class="font-semibold">Order #{{ $order->id }}</h4>
                    <p>Status: {{ $order->status->name }}</p>
                    <!-- Add more details as needed -->
                @foreach($order->positions as $position)
                    <li>{{$position->dish->name}} - ${{$position->price}} - @foreach ($position->ingredients as $pos) {{$pos->name}} @endforeach</li>
                @endforeach
                @endforeach

</x-app-layout>
