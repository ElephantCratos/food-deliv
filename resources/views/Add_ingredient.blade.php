<x-app-layout>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 mt-5">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <form method="POST" action="{{ route('ingredient.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="foodName" class="block text-gray-700 text-sm font-bold mb-2">Ingredient Name:</label>
                    <input type="text" name="name" id="foodName" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" value="" required>
                </div>
                <div class="mb-4">
                    <label for="foodName" class="block text-gray-700 text-sm font-bold mb-2">Ingridient Description:</label>
                    <input type="text" id="foodName" name="description" value="" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" required>
                </div>
                <div class="mb-4">
                    <label for="foodPrice" class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
                    <input type="text" id="foodPrice" name="price" value="" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" required>
                </div>


                <button class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" type="submit" name="submitForm" class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-90%">Submit</button>

            </form>
        </div>
    </div>
</x-app-layout>
