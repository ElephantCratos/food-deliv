<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Добавить промокод</h1>
        
        <form action="{{ route('promocode.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-2">Код промокода*:</label>
                    <input type="text" name="code" required 
                           class="w-full px-3 py-2 border rounded">
                </div>
                
                <div>
                    <label class="block mb-2">Тип скидки*:</label>
                    <select name="type" required class="w-full px-3 py-2 border rounded">
                        <option value="percent">Процентная</option>
                        <option value="fixed">Фиксированная сумма</option>
                    </select>
                </div>
                
                <div>
                    <label class="block mb-2">Размер скидки*:</label>
                    <input type="number" step="0.01" name="discount" required 
                           class="w-full px-3 py-2 border rounded">
                </div>
                
                <div>
                    <label class="block mb-2">Лимит использований:</label>
                    <input type="number" name="usage_limit" 
                           class="w-full px-3 py-2 border rounded">
                </div>
                
                <div>
                    <label class="block mb-2">Дата начала*:</label>
                    <input type="datetime-local" name="valid_from" required 
                           class="w-full px-3 py-2 border rounded">
                </div>
                
                <div>
                    <label class="block mb-2">Дата окончания*:</label>
                    <input type="datetime-local" name="valid_to" required 
                           class="w-full px-3 py-2 border rounded">
                </div>
            </div>
            
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1" checked 
                       class="mr-2 rounded">
                <label for="is_active">Активен</label>
            </div>
            
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                Создать промокод
            </button>
        </form>
    </div>
</x-app-layout>