<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showCategoriesToCustomer()
    {
        $categories = Category::OrderBy('name')
            ->get();

       return view('Edit_menu',compact([
           'categories'
       ]));
    }
}
