@if($promocode)
    <div class="flex justify-between items-center p-3 rounded-lg mb-3 bg-gray-50">
        <div class="flex items-center space-x-3">
            <span class="font-medium">Промокод:</span>
            <span class="text-green-600">{{ $promocode->code }}</span>
            <span class="px-2 py-1 bg-green-100 text-green-800 text-sm rounded-lg">
                {{ $promocode->type === 'percent' ? $promocode->discount.'%' : $promocode->discount.'₽' }}
            </span>
            <span class="text-green-600">Скидка: {{ $discountAmount }}₽</span>
        </div>
        <button type="button" class="remove-promo text-red-500 hover:text-red-700 font-medium text-sm">Удалить</button>
    </div>
@else
    <div>
        <label class="block font-medium mb-1">Промокод</label>
        <div class="flex space-x-2">
            <input type="text" id="promocodeInput" 
                   class="flex-1 p-2 border border-gray-300 rounded-lg" 
                   placeholder="Введите промокод"
                   value="{{ old('promocode') }}">
            <button type="button" onclick="applyPromocode()" 
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">
                Применить
            </button>
        </div>
    </div>
@endif