<x-app-layout>
    <form method="POST" action="{{ route('ingredient.update', $ingredient->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="item">
            <div class="text-white">
                <label for="ingredientName">Ingredient Name:</label>
                <input type="text" id="ingredientName" name="name" value="{{ $ingredient->name }}" required>
            </div>
            <h3 class="text-white">
                <label for="ingredientDescription">Description:</label>
                <input type="text" id="ingredientDescription" name="description" value="{{ $ingredient->description }}" required>
            </h3>
            <p class="text-white">
                <label for="ingredientPrice">Price:</label>
                <input type="text" id="ingredientPrice" name="price" value="{{ $ingredient->price }}" required>
            </p>
            <button type="submit" name="submitForm" class="text-white">Update</button>
        </div>
    </form>
</x-app-layout>
