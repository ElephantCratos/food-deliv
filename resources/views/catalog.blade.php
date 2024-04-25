@extends('layouts.baseLayout')

<<<<<<< HEAD
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@section('title')
    Food Delivery Catalog
@endsection
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
=======
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Food Delivery Catalog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
>>>>>>> 5ddededec756b6acf6bc250b5de367f77598bb95

@section('nav')
    @parent
@endsection

@section('content')
    <section id="menu" class="container mx-auto px-4 py-8">

<<<<<<< HEAD
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

=======
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin: 0 1rem;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        section#menu {
            display: flex;
            flex-wrap: wrap;
            padding: 1rem;
            justify-content: space-between;
        }

        .item {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 1rem;
            margin: 1rem 0;
            width: calc(33.33% - 2rem);
            text-align: center;
        }

        .item img {
            width: 100%;
        }

        .item h3 {
            margin: 1rem 0;
        }

        .item p {
            margin: 0;
            color: #777;
        }

        .item button {
            background-color: #f1c40f;
            border: none;
            color: #fff;
            padding: 0.5rem;
            margin: 1rem 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .item button:hover {
            background-color: #e0b90f;
        }

        section#cart {
            background-color: #f1c40f;
            padding: 1rem;
            color: #fff;
            text-align: center;
        }

        section#cart ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        section#cart li {
            margin: 1rem 0;
        }

        section#cart button {
            background-color: #fff;
            border: none;
            color: #f1c40f;
            padding: 0.5rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        section#cart button:hover {
            background-color: #e0b90f;
        }

        footer {
            background-color: #f1c40f;
            padding: 1rem;
            text-align: center;
        }

        footer p {
            margin: 0;
            color: #fff;
        }
    </style>
</head>

<body>
    <header>
        <h1>Welcome to Our Food Delivery Catalog</h1>
        <nav>
            <ul>
                <li><a href="{{route('profile.edit')}}">Profile</a></li>
                <li><a href="#">Menu</a></li>
                <li><a href="#">Order Now</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </nav>
    </header>

    <section id="menu">


        <div class="item ">
        <img src="food1.jpg" alt="Food Item 1">
        <h3>Food Item 1</h3>
        <p>Description of Food Item 1</p>
        <p>Price: $10.99</p>
        <label for="toppings">Choose Toppings:</label>
    <div id="toppings">
        <label><input type="checkbox" name="topping" value="cheese"> Cheese</label>
        <label><input type="checkbox" name="topping" value="pepperoni"> Pepperoni</label>
        <label><input type="checkbox" name="topping" value="mushrooms"> Mushrooms</label>
        <label><input type="checkbox" name="topping" value="olives"> Olives</label>
    </div>
            <label for="quantity">Quantity:</label><br>
    <button>Add to Cart</button>
</div>

    @foreach ($Dish as $dish)
    <div class="item ">
        <img src="food1.jpg" alt="Food Item 1">
        <h3>{{$dish->name}}</h3>
        <p>${{$dish->price}}</p>
        <label for="toppings">Choose Toppings:</label>
        <form method="POST" action="{{ route('add_to_cart')}}">
            @csrf
    <div id="toppings" >
        @if ($dish->ingredients->isNotEmpty())
                @foreach ($dish->ingredients as $ingredient)
                  <label><input type="checkbox" name="topping{{$ingredient->id}}"  value="{{$ingredient->id}}">{{ $ingredient->name }}</label>
                    @if (!$loop->last), @endif

                @endforeach
                @else
                <em>No ingredients found</em>

            @endif
    </div>
            <input type="hidden" name="dish_id" value="{{$dish->id}}">
            <input type="number" id="quantity" name="quantity" min="1" value="1"><br>
            <button type="submit">Add to Cart</button>
            </form>
    @endforeach



    <section id="cart">
        <h2>Your Cart</h2>
        <ul>
        </ul>
        <p>Total: $0.00</p>
        <a href="{{ route('Cart')}}"> <button type="button" class="btn btn-outline-primary float-right">CheckOut</button></a>
    </section>

    <footer>
        <p>&copy; 2024 Food Delivery Catalog. All rights reserved.</p>
    </footer>
</body>

</html>
>>>>>>> 5ddededec756b6acf6bc250b5de367f77598bb95
