<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dish_controller;
use App\Http\Controllers\Ingridient_controller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/Customer_Orders', function () {
    return view('Customer_Orders');
})->middleware(['auth', 'verified'])->name('Customer_Orders');

Route::get('/Kitchen_Orders', function () {
    return view('Kitchen_Orders');
})->middleware(['auth', 'verified'])->name('Kitchen_Orders');

Route::get('/Manager_Menu',[Dish_controller::class, 'index'])->name('Manager_Menu')->middleware(['auth', 'verified']);
Route::get('/Manager_Ingredients',[Ingridient_controller::class, 'index1'])->name('Manager_Ingredients')->middleware(['auth', 'verified']);

Route::get('/Edit_menu',[Ingridient_controller::class, 'index'])->name('Edit_menu')->middleware(['auth', 'verified']);
Route::post('/dish', [Dish_controller::class, 'store'])->name('dish.store');

Route::get('/Courier_Orders', function () {
    return view('Courier_Orders');
})->middleware(['auth', 'verified'])->name('Courier_Orders');

Route::get('/All_Orders', function () {
    return view('All_Orders');
})->middleware(['auth', 'verified'])->name('All_Orders');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/catalog', function () {
    return view('catalog');
});

Route::get('/catalog',[Dish_controller::class, 'index1'])->name('catalog');

require __DIR__.'/auth.php';
