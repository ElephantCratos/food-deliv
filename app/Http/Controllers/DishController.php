<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Order;
use App\ReadModel\CatalogList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


// ОТРЕФАКТОРЕН, НО НАДО ПРОСМОТРЕТЬ 
class DishController extends Controller
{
  
    public function showDishToManager()
    {
        $dishes = Dish::with('category')
            ->orderBy('name')
            ->get();

        return view('Manager_Menu', compact('dishes'));
    }


    public function showCatalog()
    {
        $categoriesList = Category::with('dishes')
            ->orderBy('id')
            ->get()
            ->map(function ($category) {
                return CatalogList::fromModel($category);
            });

        $dishes = Dish::all();

        return view('catalog', compact('categoriesList', 'dishes'));
    }


    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);
       
        $imagePath = $this->handleImageUpload($request->file('image_path'));

        Dish::create([
            'name' => $validated['name'],
            'image_path' => $imagePath,
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
        ]);

        return redirect()->route('Manager_Menu')
            ->with('success', 'Блюдо успешно создано');
    }


    public function edit(Dish $dish)
    {
        $categories = Category::all();
        return view('Edit_dish_menu', compact('dish', 'categories'));
    }


    public function update(Dish $dish, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($request->hasFile('image_path')) {
            $this->deleteOldImage($dish->image_path);
            $validated['image_path'] = $this->handleImageUpload($request->file('image_path'));
        }

        $dish->update($validated);

        return redirect()->route('Manager_Menu')
            ->with('success', 'Блюдо успешно обновлено');
    }


    public function destroy(Dish $dish)
    {
        $this->deleteOldImage($dish->image_path);
        $dish->delete();

        return redirect()->route('Manager_Menu')
            ->with('success', 'Блюдо успешно удалено');
    }


  

    private function handleImageUpload($imageFile)
    {
        if (!$imageFile) {
            return null;
        }

        $path = 'Images/' . time() . '.' . $imageFile->getClientOriginalExtension();
        $imageFile->move(public_path('images'), $path);
        
        return $path;
    }

    private function deleteOldImage($path)
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}