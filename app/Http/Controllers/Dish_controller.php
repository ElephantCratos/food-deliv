<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Dish;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Dish_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        $Dish = Dish::OrderBy('name')
            ->get();

       return view('Manager_Menu',compact([
           'Dish',
       ]));
    }

    public function index1()
    {
        $lastOrder = null;
        $Dish = Dish::orderBy('name')->get();

        if (Auth::check()) {
            $userId = Auth::user()->id;

            $lastOrder = Order::where('customer_id', $userId)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastOrder && $lastOrder->status_id != null) {
                $lastOrder = null;
            }
        }
        return view('catalog', compact('Dish', 'lastOrder'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:0',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imagePath = 'images/' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imagePath);
        }

        $dish = Dish::create([
            'name' => $request->name,
            'image_path' => $imagePath,
            'price' => $request->price,
        ]);

        $ingredients = Ingredient::whereIn('id', $request->input('ingredients', []))->get();

        $dish->ingredients()->attach($ingredients);

        return redirect()->route('dashboard')->with('success', 'Dish created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dish=Dish::findOrFail($id);
        $ingredients = Ingredient::OrderBy('name')
                ->get();
        return view('Edit_dish_menu',compact([
            'dish', 'ingredients'
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image_path' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:0',
        ]);

        $dish = Dish::findOrFail($id);

        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imagePath = 'images/' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imagePath);
            $validatedData['image_path'] = $imagePath;
        }

        $dish->update($validatedData);

        $ingredients = Ingredient::whereIn('id', $request->input('ingredients', []))->get();

        $dish->ingredients()->sync($ingredients);

        return redirect()->route('dashboard')->with('success', 'Dish updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $dish = Dish::findOrFail($id);

        if ($dish->image_path) {
            Storage::delete($dish->image_path);
        }

        $dish->ingredients()->detach();

        $dish->delete();

        return redirect()->route('dashboard')->with('success', 'Блюдо удалено успешно.');
    }

}
