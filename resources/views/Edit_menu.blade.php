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
        <label for="foodDescription">Description:</label>
        <textarea id="foodDescription" name="foodDescription">Description of Food Item 1</textarea>
    </p>
    <p class="text-white">
        <label for="foodPrice">Price:</label>
        <input type="text" id="foodPrice" name="foodPrice" value="$10.99">
    </p>
    <label for="toppings" class="text-white">Choose Toppings:</label>
    <div id="toppings" class="text-white">
        <div class="topping">
            <label><input type="checkbox" name="topping[]" value="cheese"> Cheese</label>
            <button class="remove-topping">Remove</button>
        </div>
        <div class="topping">
            <label><input type="checkbox" name="topping[]" value="pepperoni"> Pepperoni</label>
            <button class="remove-topping">Remove</button>
        </div>
        <div class="topping">
            <label><input type="checkbox" name="topping[]" value="mushrooms"> Mushrooms</label>
            <button class="remove-topping">Remove</button>
        </div>
        <div class="topping">
            <label><input type="checkbox" name="topping[]" value="olives"> Olives</label>
            <button class="remove-topping">Remove</button>
        </div>
    </div>
    <button type="submit" name="submitForm" class="text-white">Submit</button>
</div>
</x-app-layout>
