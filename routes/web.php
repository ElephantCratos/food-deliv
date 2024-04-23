<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/catalog', function () {
    return view('catalog');
});

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
        Route::get('/Edit_menu', function () {
            return view('Edit_menu');
        })->name('Edit_menu');

        Route::get('/All_Orders', function () {
            return view('All_Orders');
        })->name('All_Orders');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
