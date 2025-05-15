<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Создание аккаунта</h2>
    </x-slot>

    <div class="py-4 px-6 max-w-md">
        <form method="POST" action="{{ route('user-management.store') }}">
            @csrf

            <div class="mb-4">
                <label>Имя</label>
                <input type="text" name="name" class="border rounded w-full" required>
            </div>

            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" class="border rounded w-full" required>
            </div>

            <div class="mb-4">
                <label>Телефон</label>
                <input type="text" name="phone" class="border rounded w-full" placeholder="+7 (999) 123-45-67" required>
            </div>

            <div class="mb-4">
                <label>Пароль</label>
                <input type="password" name="password" class="border rounded w-full" required>
            </div>

            <div class="mb-4">
                <label>Подтверждение пароля</label>
                <input type="password" name="password_confirmation" class="border rounded w-full" required>
            </div>
            
            <div class="mb-4">
                <label>Фамилия</label>
                <input type="text" name="first_name" class="border rounded w-full" required>
            </div>

            <div class="mb-4">
                <label>Имя</label>
                <input type="text" name="second_name" class="border rounded w-full" required>
            </div>

            <div class="mb-4">
                <label>Роль</label>
                <select name="role" class="border rounded w-full" required>
                    <option value="manager">Менеджер</option>
                    <option value="courier">Курьер</option>
                </select>
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Создать</button>
        </form>
    </div>
</x-app-layout>
