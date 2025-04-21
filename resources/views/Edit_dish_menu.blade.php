<x-app-layout>
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8 mt-5">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
    <form method="POST" action="{{ route('dish.update', $dish->id)}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
            <div class="mb-4">
                <label for="image"  class="block text-gray-700 text-sm font-bold mb-2">Image</label>
                <input type="file" name="image_path" id="image" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="mb-4">
                <label for="foodName"  class="block text-gray-700 text-sm font-bold mb-2">Food Name:</label>
                <input type="text" id="foodName" name="name" value="{{ $dish->name }}" required class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>
           <div class="mb-4">
                <label for="foodPrice"  class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
                <input type="text" id="foodPrice" name="price" value="{{ $dish->price }}" required class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="mb-4">
            <label for="toppings"  class="block text-gray-700 text-sm font-bold mb-2">Choose Toppings:</label>
             <div id="toppings" class="text-gray-700">
                @foreach ($ingredients as $ingredient)
                    <div class="topping">
                        <label><input type="checkbox" name="ingredients[]" value="{{ $ingredient->id }}" @if($dish->ingredients->contains($ingredient)) checked @endif>{{ $ingredient->name }}</label>
                    </div>
                @endforeach
            </div>
            </div>
            <button type="submit" name="submitForm" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">Update</button>
        </div>
    </form>
    </div>
    </div>
</x-app-layout>
