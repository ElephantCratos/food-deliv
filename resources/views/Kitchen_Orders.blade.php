<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
                        <ul>
                            @foreach ($orders as $order)
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-4">
                <div class="p-6 bg-white border-b border-gray-200">
                                <li class="text-black">
                                    <h4 class="font-semibold">Order #{{ $order->id }}</h4>
                                    <p>Status: {{ $order->status->name }}</p>
                                    <!-- Add more details as needed -->
                                @if($order->status_id == 2 )
                                    @foreach($order->positions as $position)
                                        <li>{{$position->dish->name}} - ${{$position->price}} - @foreach ($position->ingredients as $pos) {{$pos->name}} @endforeach</li>
                                    @endforeach
                                    <form action="{{ route('kitchen.confirm', ['id' => $order->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Confirm Preparation</button>
                                    </form>
                                @endif
                                @if($order->status_id == 3)
                                 @foreach($order->positions as $position)
                                        <li>{{$position->dish->name}} - ${{$position->price}} - @foreach ($position->ingredients as $pos) {{$pos->name}} @endforeach</li>
                                    @endforeach
                                    <form action="{{ route('kitchen.transfer', ['id' => $order->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Call a courier</button>
                                    </form>
                                @endif

                                @if($order->status_id == 4)
                                 @foreach($order->positions as $position)
                                        <li>{{$position->dish->name}} - ${{$position->price}} - @foreach ($position->ingredients as $pos) {{$pos->name}} @endforeach</li>
                                    @endforeach
                                    <form action="{{ route('kitchen.courier-arrived', ['id' => $order->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Have transferred to the courier</button>
                                    </form>
                                    @endif

                                    </li>
                                    </div>
            </div>
                                    @endforeach
                        </ul>
                    @endif
                
        </div>
</x-app-layout>
