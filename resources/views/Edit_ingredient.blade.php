<x-app-layout>
    <form method="POST" action="{{ route('ingredient.update', $ingredient->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="item">
            <div>
                <label for="ingredientName" class="text-white">Ingredient Name:</label>
                <input type="text" id="ingredientName" name="name" value="{{ $ingredient->name }}" required>
            </div>
            <h3>
                <label class="text-white" for="ingredientDescription">Description:</label>
                <input type="text" id="ingredientDescription" name="description" value="{{ $ingredient->description }}" required>
            </h3>
            <p>
                <label class="text-white" for="ingredientPrice">Price:</label>
                <input type="text" id="ingredientPrice" name="price" value="{{ $ingredient->price }}" required>
            </p>
            <button type="submit" name="submitForm" class="text-white">Update</button>
        </div>
    </form>
</x-app-layout>
