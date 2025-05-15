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
<link rel="stylesheet" href="{{ asset('css/app.css') }}" />

<style>
    body { background-color: #ffffff !important; }
    .scrollbar-hide { scrollbar-width: none; -ms-overflow-style: none; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    @keyframes slide-in { 0% { transform: translateY(-20px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
    .animate-slide-in { animation: slide-in 0.4s ease-out; }
</style>
@endsection

@section('nav')
@parent
@endsection

@section('content')
<section id="menu" class="container mx-auto px-4 py-8">
    {{-- Часто заказывают --}}
    <h2 class="text-2xl mb-6">Часто заказывают</h2>
        <div class="relative">
            <!-- Левый внутренний блюр -->
            <div class="pointer-events-none absolute top-0 left-0 w-[20px] h-full z-10"
                 style="background: linear-gradient(to right, rgba(255,255,255,1), rgba(255,255,255,0));">
            </div>

            <!-- Правый внутренний блюр -->
            <div class="pointer-events-none absolute top-0 right-0 w-[20px] h-full z-10"
                 style="background: linear-gradient(to left, rgba(255,255,255,1), rgba(255,255,255,0));">
            </div>

            <!-- Контент со скроллом -->
            <div id="scroll-popular" class="flex space-x-[15px] overflow-x-auto scrollbar-hide select-none cursor-grab relative z-0">
                @foreach ($dishes as $dish)
                <div class="flex flex-col items-center text-center flex-shrink-0 w-52">
                    <img src="{{ asset($dish['image_path']) }}" alt="{{ $dish['name'] }}" class="w-48 h-48 object-cover rounded-md" />
                    <h3 class="mt-3 text-base font-semibold text-gray-900 truncate">{{ $dish['name'] }}</h3>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                        {{ Str::limit($dish['description'] ?? 'Описание пока недоступно', 26) }}
                    </p>

                    <div class="mt-4 flex flex-col items-center gap-2">
                        <span class="text-base font-semibold text-gray-900">от {{ number_format($dish['price'], 0) }} ₽</span>
                        <form class="add-to-cart-form flex items-center gap-2" method="POST" action="{{ route('add_to_cart') }}">
                            @csrf
                            <input type="hidden" name="dish_id" value="{{ $dish['id'] }}">
                            <input type="hidden" name="quantity" min="1" value="1" class="w-16 h-9 border border-gray-300 rounded text-center text-sm" />
                            <button type="submit" class="px-6 py-1.5 rounded-full text-sm bg-orange-50 text-orange-500 hover:bg-orange-100 transition">
                                Выбрать
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>


    {{-- Категории и блюда --}}
    <div class="pt-24">
        @foreach ($categoriesList as $catalog)
        <div id="category-{{ Str::slug($catalog->category) }}" class="scroll-mt-24 mb-16">
            <h3 class="text-3xl font-semibold">{{ $catalog->category }}</h3>
            @if(count($catalog->dishes) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10 mt-6">
                @foreach($catalog->dishes as $dish)
                <div class="flex flex-col items-center text-center">
                    <img src="{{ asset($dish['image_path']) }}" alt="{{ $dish['name'] }}" class="w-[250px] h-[300px] object-cover rounded-md" />
                    <h3 class="mt-3 text-base font-semibold text-gray-900 truncate">{{ $dish['name'] }}</h3>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-3">{{ $dish['description'] ?? 'Описание пока недоступно' }}</p>  {{-- Категории и блюда --}}
                    <div class="mt-4 flex flex-col items-center gap-2">
                        <span class="text-base font-semibold text-gray-900">от {{ number_format($dish['price'], 0) }} ₽</span>
                        <form class="add-to-cart-form flex items-center gap-2" method="POST" action="{{ route('add_to_cart') }}">
                            @csrf
                            <input type="hidden" name="dish_id" value="{{ $dish['id'] }}">
                            <input type="number" name="quantity" min="1" value="1" class="w-16 h-9 border border-gray-300 rounded text-center text-sm" />
                            <button type="submit" class="px-6 py-1.5 rounded-full text-sm bg-orange-50 text-orange-500 hover:bg-orange-100 transition">
                                Выбрать
                            </button>
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
    </div>
</section>
@endsection

@section('footer')
<footer class="bg-gray-100 text-gray-800 py-8 mt-10 border-t">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between gap-12">

            <!-- О НАС -->
            <div id="about" class="md:w-1/2 space-y-4">
                <h2 class="text-lg font-semibold">О нас</h2>
                <p>
                    <strong>«Шашлычный двор»</strong> – это сочное мясо, приготовленное на мангале по традиционным армянским рецептам. Мы используем только свежие продукты, фирменные маринады и проверенные временем техники приготовления.
                </p>
                <p>
                    Приглашаем насладиться вкусом настоящего шашлыка в уютной атмосфере или закажите с доставкой — мы работаем, чтобы вы ели вкусно!
                </p>
            </div>

            <!-- КОНТАКТЫ -->
            <div id="contacts" class="md:w-1/2 space-y-4">
                <h2 class="text-lg font-semibold">Контакты</h2>
                <ul class="space-y-1 text-sm">
                    <li>📍 <strong>График:</strong> Вторник – Воскресенье, с 11:00 до 23:00</li>
                    <li>📞 <strong>Телефон для заказа:</strong> <a href="tel:+79090353545" class="text-blue-600 hover:underline">+7 909 035 35 45</a></li>
                    <li>🗺️ <strong>Яндекс.Карты:</strong> 
                        <a href="https://yandex.ru/maps/org/shashlychny_dvor/81098053171/?ll=65.435334%2C62.140169" 
                           target="_blank" class="text-blue-600 hover:underline">
                            Перейти
                        </a>
                    </li>
                    <li>📍 <strong>2ГИС:</strong> 
                        <a href="https://2gis.ru/nyagan/firm/70000001094977366" 
                           target="_blank" class="text-blue-600 hover:underline">
                            Посмотреть
                        </a>
                    </li>
                    <li>💬 <strong>ВКонтакте:</strong> 
                        <a href="https://vk.com/shashliknya?from=groups" 
                           target="_blank" class="text-blue-600 hover:underline">
                            vk.com/shashliknya
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- НИЖНЯЯ СТРОКА -->
        <div class="mt-8 border-t pt-4 text-xs text-gray-500 text-center">
            &copy; {{ date('Y') }} Шашлычный двор. Все права защищены.
        </div>

</footer>
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

document.addEventListener('DOMContentLoaded', () => {
    @if(session('welcome'))
        showWelcomeToast("{{ session('welcome') }}");
    @endif

    function showWelcomeToast(message) {
        const toast = document.createElement('div');
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                </svg>
                <span>${message}</span>
            </div>
        `;
        toast.className = 'fixed top-6 right-6 bg-orange-500 text-white font-medium px-6 py-3 rounded-xl shadow-lg z-50 animate-slide-in';
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});
</script>



</script>
@endsection
