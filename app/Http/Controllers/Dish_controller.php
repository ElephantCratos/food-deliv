<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dish;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Storage;

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
           'Dish'
       ]));


    }

    public function index1()
{
    $Dish = Dish::orderBy('name')
        ->get();

   return view('catalog',compact([
       'Dish'
   ]));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:0',
        ]);

        $dish = Dish::create([
            'name' => $request->name,
            'image_path' => $request->image_path ? Storage::putFile('public/images', $request->file('image_path')) : null,
            'extra_ingredients_id' => 1,
            'price' => $request->price,
        ]);

        $ingredients = Ingredient::whereIn('id', $request->input('ingredients', []))->get();

        $dish->ingredients()->attach($ingredients);

        return redirect()->route('dashboard')->with('success', 'Dish created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'price' => 'required|numeric|min:0',
    ]);

    $dish = Dish::findOrFail($id);

    $dish->update($validatedData);

    $ingredients = Ingredient::whereIn('id', $request->input('ingredients', []))->get();

    $dish->ingredients()->sync($ingredients);

    return redirect()->route('dashboard')->with('success', 'Dish updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
