<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/Edit_menu', function () {
    return view('Edit_menu');
})->middleware(['auth', 'verified'])->name('Edit_menu');

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

require __DIR__.'/auth.php';
