@extends('layouts.baseLayout')

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@section('title')
    Food Delivery Catalog
@endsection
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

@section('nav')
    @parent
@endsection

@section('content')
    <section id="menu" class="container mx-auto px-4 py-8">

        <div class="flex flex-wrap -mx-4">
            <div class="w-full sm:w-1/2 md:w-1/3 px-4 mb-8">
                <div class="bg-white border border-gray-300 rounded p-6 h-full flex flex-col justify-between">
                    <img src="food1.jpg" alt="Food Item 1" class="w-full mb-4">
                    <h3 class="text-xl font-semibold mb-2">Food Item 1</h3>
                    <p class="text-gray-700 mb-2">Description of Food Item 1</p>
                    <p class="text-gray-700 mb-2">Price: $10.99</p>
                    <label for="toppings" class="text-gray-700">Choose Toppings:</label>
                    <div id="toppings" class="mb-4">
                        <label class="block"><input type="checkbox" name="topping" value="cheese" class="mr-2">Cheese</label>
                        <label class="block"><input type="checkbox" name="topping" value="pepperoni" class="mr-2">Pepperoni</label>
                        <label class="block"><input type="checkbox" name="topping" value="mushrooms" class="mr-2">Mushrooms</label>
                        <label class="block"><input type="checkbox" name="topping" value="olives" class="mr-2">Olives</label>
                    </div>
                    <button class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600 transition duration-300">Add to Cart</button>
                </div>
            </div>

            @foreach ($Dish as $dish)
            <div class="w-full sm:w-1/2 md:w-1/3 px-4 mb-8">
                <div class="bg-white border border-gray-300 rounded p-6 h-full flex flex-col justify-between">
                    <img src="food1.jpg" alt="Food Item 1" class="w-full mb-4">
                    <h3 class="text-xl font-semibold mb-2">{{ $dish->name }}</h3>
                    <p class="text-gray-700 mb-2">Price: ${{ $dish->price }}</p>
                    <label for="toppings" class="text-gray-700">Choose Toppings:</label>
                    <div id="toppings" class="mb-4">
                        @if ($dish->ingredients->isNotEmpty())
                        @foreach ($dish->ingredients as $ingredient)
                        <label class="block"><input type="checkbox" name="topping" value="{{ $ingredient->name }}" class="mr-2">{{ $ingredient->name }}</label>
                        @endforeach
                        @else
                        <em class="text-gray-500">No ingredients found</em>
                        @endif
                    </div>
                    <a?<button class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600 transition duration-300">Add to Cart</button>
                </div>
            </div>
            @endforeach

        </div>

    </section>

    <section id="cart" class="bg-yellow-500 py-8 fixed top-10 right-12 mr-8 mt-8">
        <div class="container mx-auto px-4">
            <h2 class="text-white text-xl mb-4">Your Cart</h2>
            <ul class="mb-4"></ul>
            <p class="text-white mb-4">Total: $0.00</p>
            <button class="bg-white text-yellow-500 py-2 px-4 rounded hover:bg-yellow-300 transition duration-300">Checkout</button>
        </div>
    </section>
@endsection

@section('footer')
    2024 Food Delivery Catalog. All rights reserved.
@endsection

