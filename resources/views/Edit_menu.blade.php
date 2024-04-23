<x-app-layout>
<div class="item">
    <div class="form-group">
         <label for="image" class="text-white">Image</label>
         <input type="file" name="image" id="image" class="text-white">
    </div>
    <h3 class="text-white">
        <label for="foodName">Food Name:</label>
        <input type="text" id="foodName" name="foodName" value="Food Item 1">
    </h3>
    <p class="text-white">
        <label for="foodPrice">Price:</label>
        <input type="text" id="foodPrice" name="foodPrice" value="$10.99">
    </p>
    <label for="toppings" class="text-white">Choose Toppings:</label>
    <div id="toppings" class="text-white">
        @foreach ($ingredient as $Ingredient)
        <div class="topping">
            <label><input type="checkbox">{{$Ingredient->name}}</label>
        </div>
        @endforeach
    </div>
    <button type="submit" name="submitForm" class="text-white">Submit</button>
</div>
</x-app-layout>
