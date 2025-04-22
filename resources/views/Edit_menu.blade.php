<x-app-layout>
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8 mt-5">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <form method="POST" action="{{ route('dish.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Image:</label>
                <input type="file" name="image_path" id="image" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="mb-4">
                <label for="foodName" class="block text-gray-700 text-sm font-bold mb-2">Food Name:</label>
                <input type="text" id="foodName" name="name" value="" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" required>
            </div>
            <div class="mb-4">
                <label for="foodPrice" class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
                <input type="text" id="foodPrice" name="price" value="" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" required>
            </div>
            <div class="mb-4">
                <label for="category" class="block text-gray-700 text-sm font-bold mb-2">
                    Выберите категорию:
                </label>
                <select id="category" name="category_id" class="block w-full p-2 border border-gray-300 rounded">
                    <option value="">-- Выберите категорию --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label for="toppings" class="block text-gray-700 text-sm font-bold mb-2">Choose Toppings:</label>
                <div id="toppings" class="text-gray-700">
                    @foreach ($ingredient as $Ingredient)
                        <div class="topping">
                            <label><input type="checkbox" name="ingredients[]" value="{{ $Ingredient->id }}">{{ $Ingredient->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <button class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" type="submit" name="submitForm" class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-90%">Submit</button>

        </form>
    </div>
</div>
    </x-app-layout>
