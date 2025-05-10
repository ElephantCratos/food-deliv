@extends('layouts.baseLayout')

@section('content')
@php
  $user = Auth::user();
@endphp

<div class="max-w-7xl mx-auto px-6 py-8">
  @if (session('status'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
      <span class="block sm:inline">{{ session('status') }}</span>
    </div>
  @endif

  {{-- Личные данные --}}
  <div class="mb-12">
    <h2 class="text-2xl font-semibold mb-6">Личные данные</h2>

    {{-- Имя пользователя --}}
    <div class="mb-4 max-w-md">
      <label class="block text-sm text-gray-700 mb-1">Имя</label>
      <div class="flex items-center space-x-2">
        <form id="nameForm" action="{{ route('profile_custom.update') }}" method="POST" class="w-full hidden">
          @csrf
          @method('PATCH')
          <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
          <x-input-error class="mt-2" :messages="$errors->get('name')" />
          <div class="flex space-x-2 mt-3">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Сохранить</button>
            <button type="button" id="cancelEditButton" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Отмена</button>
          </div>
        </form>

        <div id="nameDisplay" class="flex items-center space-x-2 w-full">
          <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-sm" value="{{ $user->name ?? '' }}" disabled />
          <button id="editNameButton" class="text-blue-500 text-sm hover:underline">Изменить</button>
        </div>
      </div>
    </div>

    {{-- Email пользователя --}}
    <div class="mb-4 max-w-md">
      <label class="block text-sm text-gray-700 mb-1">Email</label>
      <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-sm" value="{{ $user->email ?? '' }}" disabled />
    </div>

    {{-- Номер телефона --}}
    <div class="mb-4 max-w-md">
      <label class="block text-sm text-gray-700 mb-1">Номер телефона</label>
      <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-sm" value="{{ $user->phone ?? '+7 ...' }}" disabled />
    </div>

    {{-- Дата рождения --}}
    <div class="mb-4 max-w-xs">
      <label class="block text-sm text-gray-700 mb-1">Дата рождения</label>
      <input id="birthdate" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Выберите дату" />
    </div>

    {{-- Пол пользователя --}}
    <div class="mb-6 max-w-xs">
      <label class="block text-sm text-gray-700 mb-1">Пол</label>
      <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <option disabled selected>Выберите</option>
        <option>Мужской</option>
        <option>Женский</option>
        <option>Не указывать</option>
      </select>
    </div>

    {{-- Уведомления --}}
    <div class="mb-6">
      <label class="inline-flex items-center">
        <input type="checkbox" class="form-checkbox h-4 w-4 text-orange-500 rounded">
        <span class="ml-2 text-sm text-gray-700">Разрешаю уведомления, пуши и пр.</span>
      </label>
    </div>

    {{-- Кнопка сохранения --}}
    <button class="bg-orange-500 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-600">Сохранить</button>
  </div>

  {{-- Кнопка для открытия модалки --}}
  <div class="mb-8">
    <button 
      class="bg-blue-500 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-600"
      onclick="document.getElementById('orderModal').classList.remove('hidden')">
      Открыть историю заказов
    </button>
  </div>

  {{-- Модалка для истории заказов --}}
  <div id="orderModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg p-6 w-3/4 max-w-3xl">
      <h2 class="text-2xl font-semibold mb-4">История заказов</h2>

      @if(!empty($Orders))
        <p class="text-sm text-gray-500 mb-4">{{ count($Orders) }} заказов за последние 90 дней</p>
      @else
        <p class="text-sm text-gray-500 mb-4">Нет заказов за последние 90 дней</p>
      @endif

      <div class="space-y-4">
        @forelse ($Orders as $order)
          <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-md">
            <h4 class="font-semibold text-black">Order #{{ $order->id }}</h4>
            <p class="text-black">Status: {{ $order->status->name }}</p>

            <ul class="list-disc list-inside text-black mt-2 mb-2 space-y-1">
              @foreach($order->positions as $position)
                <li>{{ $position->dish->name }} - {{ $position->price }} ₽</li>
                <li>Количество: {{ $position->quantity }}</li>
              @endforeach
            </ul>

            <p class="text-gray-800 font-semibold">Address: {{ $order->address }}</p>
            <p class="text-gray-800 font-semibold">Expected at: {{ $order->expected_at }}</p>
            <p class="text-gray-800 font-semibold">Comment: {{ $order->comment }}</p>
            <p class="text-gray-800 font-semibold">Total Price: {{ $order->price }} ₽</p>

            @if($order->status_id == 1)
              <form action="{{ route('declineByCustomer', ['id' => $order->id]) }}" method="POST" class="mt-3">
                @csrf
                @method('POST')
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Decline</button>
              </form>
            @endif
          </div>
        @empty
          <p class="text-gray-600 text-sm">У вас пока нет заказов.</p>
        @endforelse
      </div>

      {{-- Кнопка закрытия модалки --}}
      <div class="flex justify-end">
        <button 
          class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-600"
          onclick="document.getElementById('orderModal').classList.add('hidden')">
          Закрыть
        </button>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      flatpickr("#birthdate", {
        dateFormat: "d.m.Y",
        maxDate: "today",
        defaultDate: "1984-11-15",
        locale: flatpickr.l10ns.ru
      });

      // Функция для переключения между отображением имени и формой редактирования
      const editButton = document.getElementById('editNameButton');
      const nameDisplay = document.getElementById('nameDisplay');
      const nameForm = document.getElementById('nameForm');

      editButton.addEventListener('click', function () {
        nameDisplay.classList.add('hidden');
        nameForm.classList.remove('hidden');
      });
    });
  </script>
@endsection
