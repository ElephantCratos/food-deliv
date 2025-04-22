@extends('layouts/baseLayout') 

@section('head')
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@section('title')
Food Delivery Catalog
@endsection

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
<!-- Custom Styles -->
<link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
@endsection

@section('nav')
@parent
@endsection

@section('content')
    <section id="menu" class="container mx-auto px-4 py-8">
        {{-- Часто заказывают --}}
        <h2 class="text-2xl font-bold mb-4">Часто заказывают</h2>
        <div class="relative overflow-x-auto">
            <div id="scroll-popular" class="flex space-x-4 py-4 snap-x scroll-smooth w-full h-[300px]"> 
                @foreach ($Dish as $dish)
                    <div class="bg-white border border-gray-300 rounded p-4 w-[150px] flex-none snap-center"> <!-- Уменьшен размер карточки -->
                        {{-- Картинка --}}
                        <div class="h-[120px] w-full overflow-hidden mb-4 flex items-center justify-center"> 
                            <img src="{{ asset($dish->image_path) }}" alt="{{ $dish->name }}" class="object-cover w-full h-full">
                        </div>

                        {{-- Контент --}}
                        <div class="flex flex-col justify-between flex-grow">
                            <div>
                                <h3 class="text-sm font-semibold mb-2">{{ $dish->name }}</h3> <!-- Уменьшен шрифт -->
                                <p class="text-xs text-gray-700 mb-2">от ${{ $dish->price }}</p> <!-- Уменьшен шрифт -->
                            </div>

                            <form method="POST" action="{{ route('add_to_cart') }}" class="mt-2">
                                @csrf
                                <input type="hidden" name="dish_id" value="{{ $dish->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-yellow-500 text-white py-1 px-2 rounded text-sm hover:bg-yellow-600 transition">Заказать</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Меню --}}
    <h2 class="text-2xl font-bold mb-4">Меню</h2>
    <div class="flex flex-wrap -mx-4">
        @foreach ($Dish as $dish)
            <div class="w-full sm:w-1/2 md:w-1/3 px-4 mb-8">
                <div class="bg-white border border-gray-300 rounded p-4 h-[530px] flex flex-col">

                    {{-- Картинка --}}
                    <div class="h-[250px] w-full overflow-hidden mb-4 flex items-center justify-center">
                        <img src="{{ asset($dish->image_path) }}" alt="{{ $dish->name }}" class="object-cover w-full h-full">
                    </div>

                    {{-- Контент --}}
                    <div class="flex flex-col justify-between flex-grow">
                        <div>
                            <h3 class="text-xl font-semibold mb-2">{{ $dish->name }}</h3>
                            <p class="text-gray-700 mb-2">Price: ${{ $dish->price }}</p>

                            <label for="toppings{{ $dish->id }}" class="text-gray-700">Choose Toppings:</label>
                            <div id="toppings{{ $dish->id }}" class="mb-2">
                                @if ($dish->ingredients->isNotEmpty())
                                    @foreach ($dish->ingredients as $ingredient)
                                        <label class="block">
                                            <input type="checkbox" name="topping{{$ingredient->id}}" value="{{$ingredient->id}}" class="mr-2">
                                            {{ $ingredient->name }}
                                        </label>
                                    @endforeach
                                @else
                                    <em>No ingredients found</em>
                                @endif
                            </div>
                        </div>

                        <form method="POST" action="{{ route('add_to_cart') }}">
                            @csrf
                            <input type="hidden" name="dish_id" value="{{$dish->id}}">
                            <div class="flex items-center mt-2">
                                <button type="submit" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600 transition duration-300 mr-2">Add to Cart</button>
                                <input class="w-16 border-2 border-gray-300 rounded" type="number" name="quantity" min="1" value="1">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection

@section('footer')
2024 Food Delivery Catalog. All rights reserved.
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const scrollContainer = document.getElementById('scroll-popular');
            let scrollStep = 2;

            function autoScroll() {
                if (scrollContainer.scrollLeft + scrollContainer.clientWidth >= scrollContainer.scrollWidth) {
                    scrollContainer.scrollLeft = 0;  // Сбрасываем в начало при достижении конца
                } else {
                    scrollContainer.scrollLeft += scrollStep;
                }
            }

            setInterval(autoScroll, 80); // Медленная прокрутка
        });
    </script>
@endsection
