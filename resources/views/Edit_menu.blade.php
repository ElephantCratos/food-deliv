<x-app-layout>
<form method="POST" action="{{ route('dish.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="item">
            <div class="form-group">
                <label for="image" class="text-white">Image</label>
                <input type="file" name="image_path" id="image" class="text-white">
            </div>
            <h3 class="text-white">
                <label for="foodName">Food Name:</label>
                <input type="text" id="foodName" name="name" value="Food Item 1" required>
            </h3>
            <p class="text-white">
                <label for="foodPrice">Price:</label>
                <input type="text" id="foodPrice" name="price" value="$10.99" required>
            </p>
            <label for="toppings" class="text-white">Choose Toppings:</label>
            <div id="toppings" class="text-white">
                @foreach ($ingredient as $Ingredient)
                    <div class="topping">
                        <label><input type="checkbox" name="ingredients[]" value="{{ $Ingredient->id }}">{{ $Ingredient->name }}</label>
                    </div>
                @endforeach
            </div>
            <button type="submit" name="submitForm" class="text-white">Submit</button>
        </div>
    </form>
</x-app-layout>
