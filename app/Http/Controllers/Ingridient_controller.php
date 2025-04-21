<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class Ingridient_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredient = Ingredient::OrderBy('name')
            ->get();

       return view('Edit_menu',compact([
           'ingredient'
       ]));
    }

    public function index1()
    {
        $ingredient = Ingredient::OrderBy('name')
            ->get();

       return view('Manager_Ingredients',compact([
           'ingredient'
       ]));
    }

    public function index2()
    {
        $ingredient = Ingredient::OrderBy('name')
            ->get();

       return view('Add_ingredient',compact([
           'ingredient'
       ]));
    }

    //ЕСЛИ В СЛЕДУЮЩЕМ КОМИТЕ ТУТ БУДЕТ 3 ИНДЕКСА Я НАХУЙ ГОЛОВУ ОТКУШУ/
    // ┌∩┐(◣_◢)┌∩┐ ┌∩┐(◣_◢)┌∩┐ ┌∩┐(◣_◢)┌∩┐ ┌∩┐(◣_◢)┌∩┐ ┌∩┐(◣_◢)┌∩┐
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $ingredient = Ingredient::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return redirect()->route('dashboard')->with('success', 'Dish created successfully.');
    }

  
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $ingredient = Ingredient::findOrFail($id);
    return view('edit_ingredient', compact('ingredient'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
    ]);

    $ingredient = Ingredient::findOrFail($id);
    $ingredient->update($validatedData);

    return redirect()->route('dashboard')->with('success', 'Ingredient updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $ingredient = Ingredient::findOrFail($id);
    $ingredient->delete();

    return redirect()->route('dashboard')->with('success', 'Dish deleted successfully.');
    }
}
