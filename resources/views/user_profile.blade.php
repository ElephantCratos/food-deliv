@extends('layouts.baseLayout')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

  {{-- Блок: Личные данные --}}
  <div class="mb-12">
    <h2 class="text-2xl font-semibold mb-6">Личные данные</h2>

    <div class="mb-4 max-w-md">
      <label class="block text-sm text-gray-700 mb-1">Имя</label>
      <div class="flex items-center space-x-2">
        <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-sm" value="Михаил" disabled />
        <button class="text-blue-500 text-sm hover:underline">Изменить</button>
      </div>
    </div>

    <div class="mb-4 max-w-md">
      <label class="block text-sm text-gray-700 mb-1">Номер телефона</label>
      <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-sm" value="+7 903 589 32 79" disabled />
    </div>

    <div class="mb-4 max-w-xs">
      <label class="block text-sm text-gray-700 mb-1">Дата рождения</label>
      <input
        id="birthdate"
        type="text"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
        placeholder="Выберите дату"
      />
    </div>
   

    <div class="mb-6 max-w-xs">
      <label class="block text-sm text-gray-700 mb-1">Пол</label>
      <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <option disabled selected>Выберите</option>
        <option>Мужской</option>
        <option>Женский</option>
        <option>Не указывать</option>
      </select>
    </div>

    <div class="mb-6">
      <label class="inline-flex items-center">
        <input type="checkbox" class="form-checkbox h-4 w-4 text-orange-500 rounded">
        <span class="ml-2 text-sm text-gray-700">Разрешаю уведомления, пуши и пр.</span>
      </label>
    </div>

    <button class="bg-orange-500 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-600">Сохранить</button>
  </div>

  {{-- Блок: История заказов --}}
  <div class="max-w-5xl">
    <h2 class="text-2xl font-semibold mb-2">История заказов</h2>
    <p class="text-sm text-gray-500 mb-4">20 заказов за последние 90 дней</p>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-700 border-t border-b border-gray-200">
          <tr>
            <th class="px-4 py-2">№</th>
            <th class="px-4 py-2">Время заказа</th>
            <th class="px-4 py-2">Сумма</th>
            <th class="px-4 py-2">Способ оплаты</th>
            <th class="px-4 py-2">Чек</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr>
            <td class="px-4 py-2">622</td>
            <td class="px-4 py-2">4 апр. 2025 г., 22:33</td>
            <td class="px-4 py-2">1 086 ₽</td>
            <td class="px-4 py-2">–</td>
            <td class="px-4 py-2 text-orange-500 hover:underline cursor-pointer">Посмотреть</td>
          </tr>
          {{-- Дополнительные строки... --}}
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection

@section('scripts')
  {{-- Стили (можно оставить как есть) --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  {{-- JS в правильном порядке --}}
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      flatpickr("#birthdate", {
        dateFormat: "d.m.Y",
        maxDate: "today",
        defaultDate: "1984-11-15",
        locale: flatpickr.l10ns.ru // Важно: используем объект, а не строку
      });
    });
  </script>
@endsection

