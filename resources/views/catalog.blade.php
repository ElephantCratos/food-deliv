@extends('layouts/baseLayout')

@section('head')
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('title')
Food Delivery Catalog
@endsection

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
<!-- Custom Styles -->
<link rel="stylesheet" href="{{ asset('css/styles.css') }}" />

<style>
    .scrollbar-hide {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE 10+ */
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }
</style>
@endsection

@section('nav')
@parent
@endsection

@section('content')
<section id="menu" class="container mx-auto px-4 py-8">
    {{-- Часто заказывают --}}
    <h2 class="text-2xl font-bold mb-6">Часто заказывают</h2>
    <div class="relative overflow-x-hidden">
        <div id="scroll-popular" class="flex space-x-4 py-4 overflow-x-auto scrollbar-hide select-none cursor-grab">
            @foreach ($dishes as $dish)
            <div class="dish-card bg-white rounded-2xl shadow-md hover:shadow-lg transition flex-shrink-0 w-60 h-[260px] flex flex-col overflow-hidden select-none">
                {{-- Картинка --}}
                <div class="w-full h-32 overflow-hidden">
                    <img src="{{ asset($dish['image_path']) }}" alt="{{ $dish['name'] }}" class="w-full h-full object-cover">
                </div>
                {{-- Контент --}}
                <div class="flex flex-col justify-between flex-1 p-4 min-h-0">
                    <div class="text-center">
                        <h3 class="text-base font-semibold text-gray-900 leading-tight truncate">{{ $dish['name'] }}</h3>
                        <p class="text-gray-500 text-sm mt-1 mb-2 whitespace-nowrap">от {{ number_format($dish['price'], 2) }}₽</p>
                    </div>
                    <form class="add-to-cart-form w-full flex items-center justify-center gap-2 mt-auto">
                        @csrf
                        <input type="hidden" name="dish_id" value="{{ $dish['id'] }}">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm py-2 px-4 rounded-lg transition whitespace-nowrap">
                            В корзину
                        </button>
                        <input type="number" name="quantity" min="1" value="1" class="w-14 h-10 border-2 border-gray-300 rounded-lg text-center text-sm">
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Категории и блюда --}}
    @foreach ($categoriesList as $catalog)
        <div id="category-{{ Str::slug($catalog->category) }}" class="scroll-mt-24 mb-16">
            <h3 class="text-3xl font-bold mb-6">{{ $catalog->category }}</h3>

            @if (count($catalog->dishes) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($catalog->dishes as $dish)
                    <div class="card bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-lg transition flex flex-col">
                        {{-- Картинка --}}
                        <div class="w-full h-48 overflow-hidden">
                            <img src="{{ asset($dish['image_path']) }}" alt="{{ $dish['name'] }}" class="w-full h-full object-cover">
                        </div>
                        {{-- Контент --}}
                        <div class="p-4 flex flex-col items-center text-center">
                            <h3 class="text-base font-semibold text-gray-900">{{ $dish['name'] }}</h3>
                            <p class="text-gray-500 text-sm mt-1 mb-4">от {{ number_format($dish['price'], 2) }}₽</p>
                            <form class="add-to-cart-form w-full flex items-center justify-center gap-2">
                                @csrf
                                <input type="hidden" name="dish_id" value="{{ $dish['id'] }}">
                                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm py-2 px-4 rounded-lg transition">
                                    В корзину
                                </button>
                                <input type="number" name="quantity" min="1" value="1" class="w-14 h-10 border-2 border-gray-300 rounded-lg text-center text-sm">
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400">Нет блюд в этой категории.</p>
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
    // Дублируем контент для бесшовного цикла
    const items = Array.from(scrollContainer.children);
    items.forEach(item => scrollContainer.appendChild(item.cloneNode(true)));
    const originalWidth = scrollContainer.scrollWidth / 2;
    
    let isDragging = false;
    let startX = 0;
    let scrollStart = 0;
    let autoScrollId;
    const velocity = 0.5; // скорость автоскролла

    function autoScroll() {
        if (!isDragging) {
            scrollContainer.scrollLeft += velocity;
            if (scrollContainer.scrollLeft >= originalWidth) {
                // сразу меняем позицию, без резких дерганий
                scrollContainer.scrollLeft -= originalWidth;
            }
        }
        autoScrollId = requestAnimationFrame(autoScroll);
    }

    // Инициализируем автоскролл
    autoScrollId = requestAnimationFrame(autoScroll);

    // Pointer events для поддержки мыши и тача
    scrollContainer.addEventListener('pointerdown', (e) => {
        if ([ 'BUTTON','INPUT','SELECT','TEXTAREA','A','LABEL' ].includes(e.target.tagName)) return;
        isDragging = true;
        startX = e.clientX;
        scrollStart = scrollContainer.scrollLeft;
        cancelAnimationFrame(autoScrollId);
        scrollContainer.setPointerCapture(e.pointerId);
        scrollContainer.classList.add('cursor-grabbing');
    });

    scrollContainer.addEventListener('pointermove', (e) => {
        if (!isDragging) return;
        const dx = e.clientX - startX;
        scrollContainer.scrollLeft = scrollStart - dx;
        // цикл
        if (scrollContainer.scrollLeft >= originalWidth) scrollContainer.scrollLeft -= originalWidth;
        if (scrollContainer.scrollLeft < 0) scrollContainer.scrollLeft += originalWidth;
    });

    scrollContainer.addEventListener('pointerup', (e) => {
        if (!isDragging) return;
        isDragging = false;
        scrollContainer.classList.remove('cursor-grabbing');
        scrollContainer.releasePointerCapture(e.pointerId);
        autoScrollId = requestAnimationFrame(autoScroll);
    });

    scrollContainer.addEventListener('pointerleave', (e) => {
        if (!isDragging) return;
        isDragging = false;
        scrollContainer.classList.remove('cursor-grabbing');
        scrollContainer.releasePointerCapture(e.pointerId);
        autoScrollId = requestAnimationFrame(autoScroll);
    });

    // Плавное появление карточек
    const cards = document.querySelectorAll('.dish-card');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('opacity-100', 'translate-y-0');
            }
        });
    }, { threshold: 0.2 });
    cards.forEach(card => {
        card.classList.add('opacity-0', 'translate-y-2', 'transition-all', 'duration-500');
        observer.observe(card);
    });

    // Добавление в корзину без перезагрузки
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const response = await fetch("{{ route('add_to_cart') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });
            if (response.ok) showToast("Добавлено в корзину!");
            else showToast("Ошибка при добавлении", true);
        });
    });

    function showToast(message, isError = false) {
        const toast = document.createElement('div');
        toast.textContent = message;
        toast.className = `fixed bottom-6 right-6 px-4 py-2 rounded-lg shadow-lg text-white z-50 transition duration-300 ${isError ? 'bg-red-500' : 'bg-green-500'}`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }
});
</script>
@endsection
