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

@endsection

@section('nav')
@parent
@endsection

@section('content')
<section id="menu" class="container mx-auto px-4 py-8">

    <div class="flex flex-wrap -mx-4">
        @foreach ($Dish as $dish)
        <div class="w-full sm:w-1/2 md:w-1/3 px-4 mb-8">
            <div class="bg-white border border-gray-300 rounded p-6 h-full flex flex-col justify-between">
                <img src="food1.jpg" alt="Food Item 1" class="w-full mb-4">
                <h3 class="text-xl font-semibold mb-2">{{ $dish->name }}</h3>
                <p class="text-gray-700 mb-2">Price: ${{ $dish->price }}</p>
                <label for="toppings" class="text-gray-700">Choose Toppings:</label>
                <form method="POST" action="{{ route('add_to_cart')}}">
                    @csrf
                    <div id="toppings" class="mb-4">
                        @if ($dish->ingredients->isNotEmpty())
                        @foreach ($dish->ingredients as $ingredient)
                        <label class="block"><input type="checkbox" name="topping{{$ingredient->id}}" value="{{$ingredient->id}}" class="mr-2">{{ $ingredient->name }}</label>
                        @if (!$loop->last) @endif

                        @endforeach
                        @else
                        <em>No ingredients found</em>

                        @endif
                    </div>
                    <input type="hidden" name="dish_id" value="{{$dish->id}}">
                    <div class="flex">
                        <button type="submit" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600 transition duration-300 mr-2">Add to Cart</button>
                        <input class="w-16" type="number" id="quantity" name="quantity" min="1" value="1"><br>
                    </div>
                </form>
            </div>
        </div>
        @endforeach

    </div>

</section>

<section id="cart" class="bg-yellow-500 py-8 fixed top-0 right-0 mr-8 mt-8">
    <div class="container mx-auto px-4">
        <h2 class="text-white text-xl mb-4 text-center">Your Cart</h2>
        <ul class="mb-4"></ul>
        @if($lastOrder!=null)
            <p class="text-white mb-4">Итого: {{$lastOrder->price}}</p>
        @else
            <p class="text-white mb-4">Итого: 0</p>
        @endif
        <a href="{{ route('Cart')}}"><button class="bg-white text-yellow-500 py-2 px-4 rounded hover:bg-yellow-300 transition duration-300">Checkout</button></a>
    </div>
</section>
@endsection

@section('footer')
2024 Food Delivery Catalog. All rights reserved.
@endsection