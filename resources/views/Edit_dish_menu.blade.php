<x-app-layout>
    <form method="POST" action="{{ route('dish.update', $dish->id)}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="item">
            <div class="form-group">
                <label for="image" class="text-white">Image</label>
                <input type="file" name="image_path" id="image" class="text-white">
            </div>
            <h3 class="text-white">
                <label for="foodName" class="text-white">Food Name:</label>
                <input type="text" id="foodName" name="name" value="{{ $dish->name }}" required>
            </h3>
            <p>
                <label for="foodPrice" class="text-white">Price:</label>
                <input type="text" id="foodPrice" name="price" value="{{ $dish->price }}" required>
            </p>
            <label for="toppings" class="text-white">Choose Toppings:</label>
            <div id="toppings"class="text-white">
                @foreach ($ingredients as $ingredient)
                    <div class="topping">
                        <label><input type="checkbox" name="ingredients[]" value="{{ $ingredient->id }}" @if($dish->ingredients->contains($ingredient)) checked @endif>{{ $ingredient->name }}</label>
                    </div>
                @endforeach
            </div>
            <button type="submit" name="submitForm" class="text-white">Update</button>
        </div>
    </form>
</x-app-layout>
