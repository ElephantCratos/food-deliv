<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your orders') }}
        </h2>
    </x-slot>
    <div class="flex justify-center p-4">
    <div class="order-block bg-white border border-gray-200 rounded-lg p-4 shadow-md" style="width: 70%;>
    <h3 class="text-lg font-semibold text-gray-800">Order #12345</h3>
    <p class="text-gray-600">Status: In Progress</p>
    <ul class="list-disc list-inside text-gray-600">
        <li>Pizza Margherita - $10.99</li>
        <li>Caesar Salad - $7.99</li>
        <li>Garlic Bread - $4.99</li>
    </ul>
    <p class="text-gray-800 font-semibold">Total Price: $23.97</p>
    </div>
    </div>
</x-app-layout>