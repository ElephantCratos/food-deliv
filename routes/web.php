<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderPositionController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PromocodeController;


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
    Route::post('/cart/apply-promocode', [OrderController::class, 'applyPromocode'])
    ->name('cart.apply-promocode');
    Route::post('/cart/remove-promocode', [OrderController::class, 'removePromocode'])
    ->name('cart.remove-promocode');
    Route::post('/cart/update/{dish}', [OrderPositionController::class, 'updateQuantity'])->name('cart.update');
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

    Route::get('/chats', [ChatController::class, 'index'])
    ->name('chats.index');

// Создать или открыть чат с конкретным клиентом
Route::get('/chats/open/{userId}', [ChatController::class, 'openChat'])
    ->name('chats.open');

// Просмотр конкретного чата (интерфейс диалога)
Route::get('/chats/{chat}', [ChatController::class, 'show'])
    ->name('chats.show');

// Отправка сообщения в чат
Route::post('/chats/{chat}/send', [ChatController::class, 'send'])
    ->name('chats.send');

    Route::middleware(['can:access to manager panel'])->group(function () {
        //Route::get('/Edit_menu', function () {
        //    return view('Edit_menu');
        //})->name('Edit_menu');


        Route::get('/All_Orders/{category?}', [OrderController::class, 'showOrders'])->name('All_Orders');
        Route::post('/orders/{id}/accept', [OrderController::class, 'acceptOrder'])->name('orders.accept');
        Route::post('/orders/{id}/decline', [OrderController::class, 'declineOrder'])->name('orders.decline');

        Route::post('/dish', [DishController::class, 'store'])->name('dish.store');

        Route::get('/Manager_Menu',[DishController::class, 'showDishToManager'])->name('Manager_Menu');

        Route::get('/Edit_menu',[CategoryController::class, 'showCategoriesToCustomer'])->name('Edit_menu');

        Route::get('/dish/{id}/edit', [DishController::class, 'edit'])->name('dish.edit');

        Route::put('/dish/{id}', [DishController::class, 'update'])->name('dish.update');

        Route::delete('/dish/delete/{id}', [DishController::class, 'delete'])->name('dish.delete');

        Route::resource('promocodes', App\Http\Controllers\PromocodeController::class)
        ->names('admin.promocodes');

        Route::get('/Manager_Promocodes', [PromocodeController::class, 'showPromocodesToManager'])->name('Manager_Promocodes');

        // Форма добавления промокода
        Route::get('/Add_Promocode', [PromocodeController::class, 'showRedactorPromocodes'])->name('Add_Promocode');

        // Сохранение нового промокода
        Route::post('/promocode', [PromocodeController::class, 'store'])->name('promocode.store');

        // Форма редактирования
        Route::get('/promocode/{id}/edit', [PromocodeController::class, 'edit'])->name('promocode.edit');

        // Обновление промокода
        Route::put('/promocode/{id}', [PromocodeController::class, 'update'])->name('promocode.update');

        // Удаление промокода
        Route::delete('/promocode/delete/{id}', [PromocodeController::class, 'delete'])->name('promocode.delete');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/catalog', [DishController::class, 'showCatalog'])->name('catalog');

require __DIR__ . '/auth.php';
