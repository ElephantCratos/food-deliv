<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dish;

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
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
