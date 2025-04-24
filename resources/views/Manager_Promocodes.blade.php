<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Управление промокодами</h1>
        
        <a href="{{ route('Add_Promocode') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
            Добавить промокод
        </a>
        
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border">Код</th>
                    <th class="py-2 px-4 border">Скидка</th>
                    <th class="py-2 px-4 border">Действует</th>
                    <th class="py-2 px-4 border">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promocodes as $promo)
                <tr>
                    <td class="py-2 px-4 border">{{ $promo->code }}</td>
                    <td class="py-2 px-4 border">
                        {{ $promo->discount }}{{ $promo->type === 'percent' ? '%' : '₽' }}
                    </td>
                    <td class="py-2 px-4 border">
                        {{ $promo->valid_from }} - {{ $promo->valid_to }}
                    </td>
                    <td class="py-2 px-4 border">
                        <a href="{{ route('promocode.edit', $promo->id) }}" class="text-blue-500">Ред.</a>
                        <form action="{{ route('promocode.delete', $promo->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 ml-2">Уд.</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>