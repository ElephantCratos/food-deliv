<x-app-layout>
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8 mt-5">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
    <form method="POST" action="{{ route('dish.update', $dish)}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
            <div class="mb-4">
                <label for="image"  class="block text-gray-700 text-sm font-bold mb-2">Картинка</label>
                <input type="file" name="image_path" id="image" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="mb-4">
                <label for="foodName"  class="block text-gray-700 text-sm font-bold mb-2">Название блюда:</label>
                <input type="text" id="foodName" name="name" value="{{ $dish->name }}" required class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>
           <div class="mb-4">
                <label for="foodPrice"  class="block text-gray-700 text-sm font-bold mb-2">Цена:</label>
                <input type="text" id="foodPrice" name="price" value="{{ $dish->price }}" required class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <select id="category" name="category_id" class="block w-full p-2 border border-gray-300 rounded">
                <option value="">-- Выберите категорию --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $dish->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <div class="mb-4">
            </div>
            <button type="submit" name="submitForm" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">Update</button>
        </div>
    </form>
    </div>
    </div>
</x-app-layout>
