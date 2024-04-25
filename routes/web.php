<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dish_controller;
use App\Http\Controllers\Ingridient_controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
Route::get('/', function () {
    return view('welcome');
});

//Route::get('/catalog', function () {
//    return view('catalog');
//});



Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware(['can:access to kitchen panel'])->group(function () {
        Route::get('/Kitchen_Orders', function () {
            return view('Kitchen_Orders');
        })->name('Kitchen_Orders');
    });

    Route::middleware(['can:access to user panel'])->group(function () {
        Route::get('/Customer_Orders', function () {
            return view('Customer_Orders');
        })->name('Customer_Orders');
    });

    Route::middleware(['can:access to courier panel'])->group(function () {
        Route::get('/Courier_Orders', function () {
            return view('Courier_Orders');
        })->name('Courier_Orders');
    });

    Route::middleware(['can:access to manager panel'])->group(function () {
        //Route::get('/Edit_menu', function () {
        //    return view('Edit_menu');
        //})->name('Edit_menu');

        Route::get('/All_Orders', [OrderController::class, 'index'])->name('All_Orders');

        Route::get('/Manager_Ingredients',[Ingridient_controller::class, 'index1'])->name('Manager_Ingredients');

        Route::post('/dish', [Dish_controller::class, 'store'])->name('dish.store');

        Route::post('/ingredient', [ingridient_controller::class, 'store'])->name('ingredient.store');

        Route::get('/Add_ingredient', [ingridient_controller::class, 'index2'])->name('Add_ingredient');

        Route::get('/Manager_Menu',[Dish_controller::class, 'index'])->name('Manager_Menu');

        Route::get('/Edit_menu',[Ingridient_controller::class, 'index'])->name('Edit_menu');

        Route::get('/dish/{id}/edit', [Dish_controller::class, 'edit'])->name('dish.edit');

        Route::put('/dish/{id}', [Dish_controller::class, 'update'])->name('dish.update');

        Route::get('/ingredient/{id}/edit', [Ingridient_controller::class, 'edit'])->name('ingredient.edit');
        Route::put('/ingredient/{id}', [Ingridient_controller::class, 'update'])->name('ingredient.update');

        Route::delete('/dish/delete/{id}', [Dish_controller::class, 'delete'])->name('dish.delete');
        Route::delete('/ingredient/delete/{id}', [Ingridient_controller::class, 'delete'])->name('Ingridient.delete');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/catalog',[Dish_controller::class, 'index1'])->name('catalog');

require __DIR__ . '/auth.php';
