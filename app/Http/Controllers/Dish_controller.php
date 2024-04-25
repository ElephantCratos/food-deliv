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
    public function edit(Dish $dish)
{
    return view('dish.edit', ['dish' => $dish]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dish $dish)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'price' => 'required|numeric|min:0',
    ]);

    $dish->update([
        'name' => $request->name,
        'image_path' => $request->image_path ? Storage::putFile('public/images', $request->file('image_path')) : $dish->image_path,
        'price' => $request->price,
    ]);

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

    public function dishCart()
    {
        return view('Cart');
    }

    public function addDishToCart(Request $request)
    {
        $dishId = $request->input('dish_id');
        $count = $request->input('count', 1);
        $cartDishID = $request->input('cart_dish_id');

        $dish = Dish::find($dishId);

        if(!$dish) {
            return response()->json(['error' => 
            'Dish is not found'], 404);
        }

        $cart = session()->get('cart', []);

        if(isset($cart[$dishId]))
        {
            $cart[$dishId]['count'] += $count;
        }
        else
        {
            $cart[$dishId] = [
                'id' => $dish->id,
                'name' => $dish->name,
                'image_path' => $dish->image_path,
                'extra_ingredients_id' => $dish->extra_ingredients_id,
                'price' => $dish->price,
                'count' => $count
            ];
        }

        session()->put('cart', $cart);
        $totalCount = 0;
        foreach ($cart as $item)
        {
            $totalCount += $item['count'];
        }
        return response()->json(['message' => 
        'Cart updated', 'cartCount' => $totalCount], 200);
    }

    public function deleteDishFromCart(Request $request) {
        
        if ($request->id)
        {
            $cart = session()->get('cart');

            if(isset($cart[$request->id])) 
            {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }

            session()->flash('success', 'Dish deleted.');
        }
    }
}
