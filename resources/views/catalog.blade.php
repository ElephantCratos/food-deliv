

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
            @foreach ($dishes as $dish)
                <div class="card w-[150px] flex-none snap-center">
                    {{-- Картинка --}}
                    <div class="h-[120px] w-full overflow-hidden mb-4 flex items-center justify-center"> 
                        <img src="{{ asset($dish->image_path) }}" alt="{{ $dish->name }}" class="object-cover w-full h-full">
                    </div>

                    {{-- Контент --}}
                    <div class="flex flex-col justify-between flex-grow">
                        <div>
                            <h3 class="text-sm font-semibold mb-2">{{ $dish->name }}</h3>
                            <p class="text-xs text-gray-700 mb-2">от ${{ $dish->price }}</p>
                        </div>

                        <form method="POST" action="{{ route('add_to_cart') }}" class="mt-2">
                            @csrf
                            <input type="hidden" name="dish_id" value="{{ $dish->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn-light">Заказать</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Меню --}}
    <h2 class="text-2xl font-bold mb-4">Меню</h2>
    {{-- Категории навигация --}}
    <div class="w-full bg-gray-100 py-4 mb-8 sticky top-0 z-10 bg-blur">
        <div class="container mx-auto flex flex-wrap justify-center gap-4">
            @foreach ($categoriesList as $catalog)
                <a href="#category-{{ Str::slug($catalog->category) }}" class="btn-category">
                    {{ $catalog->category }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Категории и блюда --}}
    @foreach ($categoriesList as $catalog)
        <div id="category-{{ Str::slug($catalog->category) }}" class="scroll-mt-24 mb-12">
            <h3 class="text-3xl font-bold mb-6">{{ $catalog->category }}</h3>

            @if (count($catalog->dishes) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($catalog->dishes as $dish)
                        <div class="card">
                            {{-- Картинка --}}
                            <div class="h-[160px] w-full overflow-hidden mb-4 flex items-center justify-center">
                                <img src="{{ asset($dish['image_path']) }}" alt="{{ $dish['name'] }}" class="object-cover w-full h-full rounded">
                            </div>

                            {{-- Контент --}}
                            <div class="flex flex-col justify-between flex-grow">
                                <div>
                                    <h3 class="text-lg font-semibold mb-2">{{ $dish['name'] }}</h3>
                                    <p class="text-gray-700 mb-2">Цена: ${{ $dish['price'] }}</p>
                                </div>

                                <form method="POST" action="{{ route('add_to_cart') }}">
                                    @csrf
                                    <input type="hidden" name="dish_id" value="{{ $dish['id'] }}">
                                    <div class="flex items-center mt-2">
                                        <button type="submit" class="btn-light mr-2">В корзину</button>
                                        <input type="number" name="quantity" min="1" value="1" class="w-16 border-2 border-gray-300 rounded text-center">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Нет блюд в этой категории.</p>
            @endif
        </div>
    @endforeach
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

        // Плавная прокрутка по категориям
        const categoryLinks = document.querySelectorAll('a[href^="#category-"]');
        categoryLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                window.scrollTo({
                    top: target.offsetTop - 80, // Учитываем высоту фиксированного меню
                    behavior: 'smooth',
                });
            });
        });
    });
</script>
@endsection