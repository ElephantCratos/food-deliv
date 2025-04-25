<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Ingredient;


class IngridientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showIngridientsToCustomer()
    {
        $categories = Category::OrderBy('name')
            ->get();

       return view('Edit_menu',compact([
           'categories'
       ]));
    }
}
