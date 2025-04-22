<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderPositionController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\IngridientController;
use Illuminate\Support\Facades\Route;


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

    Route::delete('/order_position/delete/{id}',[OrderPositionController::class, 'destroy']) ->name('delete-order-position');
    Route::post('/add_to_cart', [OrderPositionController::class, 'store']) -> name('add_to_cart');
    Route::get('/Cart', [OrderController::class,'showCart'])->name('Cart');
    Route::post('send_cart', [OrderController::class, 'sendOrder'])->name('send_order');

    Route::middleware(['can:access to kitchen panel'])->group(function () {
        Route::get('/Kitchen_Orders', [KitchenController::class, 'showOrdersToKitchen'])->name('Kitchen_Orders');
        Route::post('/Kitchen_Orders/{id}/confirm', [KitchenController::class, 'confirmPreparation'])->name('kitchen.confirm');
        Route::post('/Kitchen_Orders{id}/transfer', [KitchenController::class, 'transferToCourier'])->name('kitchen.transfer');
        Route::post('/Kitchen_Orders{id}/courier-arrived', [KitchenController::class, 'courierArrived'])->name('kitchen.courier-arrived');
    });

   

        Route::get('/Customer_Orders', [OrderController::class, 'showOwnOrders'])
            ->name('Customer_Orders');
       Route::post('/orders/{id}/decline_by_customer', [OrderController::class, 'declineByCustomer'])->name('declineByCustomer');

    Route::middleware(['can:access to courier panel'])->group(function () {
        Route::get('/Courier_Orders', [CourierController::class, 'showOrdersToCourier'])->name('Courier_Orders');
        Route::post('/Courier_Orders/{id}/confirm-delivery', [CourierController::class, 'confirmDelivery'])->name('courier.confirm');
        Route::post('Courier_Orders/{id}/get-order', [CourierController::class, 'acceptOrder']) -> name('courier.accept-order');
        Route::post('Courier_Orders/{id}/delivered', [CourierController::class, 'orderHasDelivered']) -> name('courier.delivered');
    });

    Route::middleware(['can:access to chat'])->group(function() {
        Route::get('/users', [ChatController::class, 'showUsers'])->name('Users_list');
        Route::get('/chat/user/{user}', [ChatController::class, 'openChat'])->name('chats.open');
        Route::get('/chat/{chat}', [ChatController::class, 'showChat']) ->name('chats.show');
        Route::post('/chat/{chat}', [ChatController::class, 'sendMessage'])->name('chats.send');
    });

    Route::middleware(['can:access to manager panel'])->group(function () {
        //Route::get('/Edit_menu', function () {
        //    return view('Edit_menu');
        //})->name('Edit_menu');


        Route::get('/All_Orders/{category?}', [OrderController::class, 'showOrders'])->name('All_Orders');
        Route::post('/orders/{id}/accept', [OrderController::class, 'acceptOrder'])->name('orders.accept');
        Route::post('/orders/{id}/decline', [OrderController::class, 'declineOrder'])->name('orders.decline');

        Route::get('/Manager_Ingredients', [IngridientController::class, 'showIngredientsToManager'])->name('Manager_Ingredients');

        Route::post('/dish', [DishController::class, 'store'])->name('dish.store');


        Route::post('/ingredient', [ingridientController::class, 'store'])->name('ingredient.store');

        Route::get('/Add_ingredient', [ingridientController::class, 'showRedactorIngridients'])->name('Add_ingredient');

        Route::get('/Manager_Menu',[DishController::class, 'showDishToManager'])->name('Manager_Menu');

        Route::get('/Edit_menu',[IngridientController::class, 'showIngridientsToCustomer'])->name('Edit_menu');

        Route::get('/dish/{id}/edit', [DishController::class, 'edit'])->name('dish.edit');

        Route::put('/dish/{id}', [DishController::class, 'update'])->name('dish.update');

        Route::get('/ingredient/{id}/edit', [IngridientController::class, 'edit'])->name('ingredient.edit');
        Route::put('/ingredient/{id}', [IngridientController::class, 'update'])->name('ingredient.update');

        Route::delete('/dish/delete/{id}', [DishController::class, 'delete'])->name('dish.delete');
        Route::delete('/ingredient/delete/{id}', [IngridientController::class, 'delete'])->name('Ingridient.delete');

    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/catalog', [DishController::class, 'showCatalog'])->name('catalog');

require __DIR__ . '/auth.php';
