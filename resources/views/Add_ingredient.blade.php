<x-app-layout>
<form method="POST" action="{{ route('ingredient.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="item">
            <div class="text-white">
                <label for="foodName">Food Name:</label>
                <input type="text" id="foodName" name="name" value="" required>
            </div>
            <h3 class="text-white">
                <label for="foodName">Food Name:</label>
                <input type="text" id="foodName" name="description" value="" required>
            </h3>
            <p class="text-white">
                <label for="foodPrice">Price:</label>
                <input type="text" id="foodPrice" name="price" value="" required>
            </p>
            <button type="submit" name="submitForm" class="text-white">Submit</button>
        </div>
    </form>
</x-app-layout>
