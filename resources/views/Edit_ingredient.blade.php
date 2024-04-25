<x-app-layout>
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8 mt-5">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
<form method="POST" action="{{ route('ingredient.update', $ingredient->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
            <div class="mb-4">
                <label for="ingredientName" class="block text-gray-700 text-sm font-bold mb-2">Ingredient Name:</label>
                <input type="text" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" id="ingredientName" name="name" value="{{ $ingredient->name }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="ingredientDescription">Description:</label>
                <input type="text" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" id="ingredientDescription" name="description" value="{{ $ingredient->description }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="ingredientPrice">Price:</label>
                <input type="text" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" id="ingredientPrice" name="price" value="{{ $ingredient->price }}" required>
            </div>
        <button type="submit" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" name="submitForm" class="text-white">Update</button>
    </form>
    </div>
    </div>
</x-app-layout>
