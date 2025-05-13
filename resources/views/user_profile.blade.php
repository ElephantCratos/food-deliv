@extends('layouts.baseLayout')

@section('content')
@php
  $user = Auth::user();
@endphp

<div class="max-w-7xl mx-auto px-6 py-8">
  {{-- Flash-сообщение --}}
  @if (session('status'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
      <span class="block sm:inline">{{ session('status') }}</span>
    </div>
  @endif

  {{-- Личные данные --}}
  <div class="mb-12">
    <h2 class="text-2xl font-semibold mb-6">Личные данные</h2>

    <form action="{{ route('profile_custom.update') }}" method="POST" class="space-y-6 max-w-md">
      @csrf
      @method('PATCH')

      <div>
        <label class="block text-sm text-gray-700 mb-1">Имя</label>
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
      </div>

      <div>
        <label class="block text-sm text-gray-700 mb-1">Email</label>
        <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-sm" value="{{ $user->email ?? '' }}" disabled />
      </div>

      <div>
        <label class="block text-sm text-gray-700 mb-1">Номер телефона</label>
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" value="{{ old('phone', $user->phone) }}" autocomplete="tel" />
        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
      </div>

      <div>
        <label class="block text-sm text-gray-700 mb-1">Дата рождения</label>
        <input id="birthdate" name="birthdate" type="text"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
               value="{{ old('birthdate', optional($user->birthdate)->format('d.m.Y')) }}"
               placeholder="Выберите дату" />
        <x-input-error class="mt-2" :messages="$errors->get('birthdate')" />
      </div>

      <div>
        <label class="inline-flex items-center">
          <input type="checkbox" name="notifications_enabled"
                 class="form-checkbox h-4 w-4 text-orange-500 rounded"
                 {{ old('notifications_enabled', $user->notifications_enabled) ? 'checked' : '' }}>
          <span class="ml-2 text-sm text-gray-700">Разрешаю уведомления, пуши и пр.</span>
        </label>
      </div>

      <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-600">
        Сохранить
      </button>
    </form>
  </div>

  <div class="max-w-5xl" x-data>
    <h2 class="text-2xl font-semibold mb-2">История заказов</h2>
    <p class="text-sm text-gray-500 mb-4">
        {{ $orders->count() }} заказ{{ Str::plural('', $orders->count()) }} за последние 90 дней
    </p>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm text-left">
      <thead class="bg-gray-100 text-gray-700 border-t border-b border-gray-200">
  <tr>
    <th class="px-4 py-2">№</th>
    <th class="px-4 py-2">Время заказа</th>
    <th class="px-4 py-2">Сумма</th>
    <th class="px-4 py-2">Способ оплаты</th>
    <th class="px-4 py-2">Статус</th> {{-- новая колонка --}}
    <th class="px-4 py-2">Чек</th>
  </tr>
</thead>
<tbody class="divide-y divide-gray-100">
  @foreach($orders as $order)
    <tr x-data="{ open: false }">
      <td class="px-4 py-2">{{ $order->id }}</td>
      <td class="px-4 py-2">
        {{ $order->created_at->locale('ru')->isoFormat('D MMM YYYY г. HH:mm') }}
      </td>
      <td class="px-4 py-2">{{ number_format($order->price, 0, ',', ' ') }} ₽</td>
      <td class="px-4 py-2">{{ $order->payment_type ?? '–' }}</td>
      <td class="px-4 py-2">{{ $order->status->toRussian() }}</td> {{-- вывод статуса --}}
      <td class="px-4 py-2">
        <button @click="open = true" class="text-orange-500 hover:underline">
          Посмотреть
        </button>

        {{-- Модалка --}}
        <div
                  x-show="open"
                  x-cloak
                  class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                >
                  <div
                    @click.away="open = false"
                    class="bg-white rounded-2xl shadow-lg max-w-2xl w-full p-6"
                  >
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-semibold">Заказ №{{ $order->id }}</h3>
              <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>

            <p class="text-sm text-gray-600 mb-2">
              Время: {{ $order->created_at->locale('ru')->isoFormat('D MMM YYYY г. HH:mm') }}
            </p>
            <p class="text-sm text-gray-600 mb-2">
              Статус: <strong>{{ $order->status->toRussian() }}</strong>
            </p>
            @if($order->promocode)
  <div class="border-t border-gray-200 pt-4 mb-4">
    {{-- Промокод --}}
    <p class="text-sm text-gray-700 mb-2">
      <span class="font-semibold">Промокод:</span>
      <span class="text-blue-600">{{ $order->promocode }}</span>
    </p>

    @php
      // Конечная цена из БД (уже с учётом скидки)
      $finalPrice = $order->price;

      if ($order->promocode_type === 'percent') {
          // Если скидка в процентах, восстанавливаем первоначальную цену
          $p = $order->promocode_discount / 100;
          $originalPrice = $p < 1
              ? round($finalPrice / (1 - $p))
              : $finalPrice;
          $discountRub = $originalPrice - $finalPrice;
      } else {
          // Если фиксированная сумма скидки
          $discountRub = $order->promocode_discount;
          $originalPrice = $finalPrice + $discountRub;
      }
    @endphp

    {{-- Показываем зачёркнутую оригинальную цену и итоговую --}}
    <p class="text-lg">
      <span class="line-through text-gray-400 mr-3">
        {{ number_format($originalPrice, 0, ',', ' ') }} ₽
      </span>
      <span class="font-semibold text-green-600">
        {{ number_format($finalPrice, 0, ',', ' ') }} ₽
      </span>
    </p>
  </div>
@endif



            <p class="text-sm text-gray-600 mb-4">
              Сумма: {{ number_format($order->price, 0, ',', ' ') }} ₽
            </p>

            <div class="overflow-x-auto">
              <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700">
                  <tr>
                    <th class="px-3 py-2">Блюдо</th>
                    <th class="px-3 py-2">Кол-во</th>
                    <th class="px-3 py-2">Цена</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->positions as $pos)
                    <tr class="border-b">
                      <td class="px-3 py-2">{{ $pos->dish->name }}</td>
                      <td class="px-3 py-2">{{ $pos->quantity }}</td>
                      <td class="px-3 py-2">{{ number_format($pos->price, 0, ',', ' ') }} ₽</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="mt-6 text-right">
              <button @click="open = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Закрыть
              </button>
            </div>
          </div>
        </div>
        {{-- /Модалка --}}
      </td>
    </tr>
  @endforeach
</tbody>

      </table>
    </div>
</div>



@endsection

@section('scripts')
  {{-- Alpine.js --}}
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  {{-- Flatpickr --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      flatpickr("#birthdate", {
        dateFormat: "d.m.Y",
        maxDate: "today",
        locale: flatpickr.l10ns.ru,
      });
    });

    function orderHistory() {
      return {
        modalVisible: false,
        currentOrder: null,
        openModal(order) {
          this.currentOrder = order;
          this.modalVisible = true;
        }
      };
    }
  </script>
@endsection
