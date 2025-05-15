<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderPositionController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\EnsureAuthenticated;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PromocodeController;
use App\Http\Controllers\CourierAssignmentController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserProfileController;


Route::get('/', function () {
    return view('welcome');
});

//Route::get('/catalog', function () {
//    return view('catalog');
//});

Route::get('/profile/custom', [UserProfileController::class, 'index'])->name('profile_custom');
Route::patch('/profile/custom', [UserProfileController::class, 'update'])->name('profile_custom.update');

Route::middleware(['auth', 'can:access to manager panel'])->group(function () {
    Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management.index');
    Route::get('/user-management/create', [UserManagementController::class, 'create'])->name('user-management.create');
    Route::post('/user-management', [UserManagementController::class, 'store'])->name('user-management.store');
    Route::delete('/user-management/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
});

Route::get('/Cart', [OrderController::class,'showCart'])->name('Cart')->middleware(EnsureAuthenticated::class);


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/kitchen-camera', [CameraController::class, 'showCamera'])->name('kitchen.camera');
    Route::delete('/order_position/delete/{id}',[OrderPositionController::class, 'destroy']) ->name('delete-order-position');
    Route::post('/add_to_cart', [OrderPositionController::class, 'store']) -> name('add_to_cart');

    Route::post('/cart/apply-promocode', [OrderController::class, 'applyPromocode'])
    ->name('cart.apply-promocode');
    Route::post('/cart/remove-promocode', [OrderController::class, 'removePromocode'])
    ->name('cart.remove-promocode');
    Route::post('/cart/update/{dish}', [OrderPositionController::class, 'updateQuantity'])->name('cart.update');
    Route::post('send_cart', [OrderController::class, 'sendOrder'])->name('send_order');
    Route::patch('/cart/positions/{id}/quantity', [OrderPositionController::class, 'updateQuantity'])->name('cart.positions.updateQuantity');

    Route::middleware(['can:access to kitchen panel'])->group(function () {
        Route::get('/Kitchen_Orders', [KitchenController::class, 'showOrdersToKitchen'])->name('Kitchen_Orders');
        Route::post('/Kitchen_Orders{id}/transfer', [KitchenController::class, 'markAsReady'])
        ->name('kitchen.ready');
        Route::post('/Kitchen_Orders{id}/courier-arrived', [KitchenController::class, 'courierArrived'])->name('kitchen.courier-arrived');
    });

   

        Route::get('/Customer_Orders', [OrderController::class, 'showOwnOrders'])
            ->name('Customer_Orders');
       Route::post('/orders/{id}/decline_by_customer', [OrderController::class, 'declineByCustomer'])->name('declineByCustomer');

    Route::middleware(['can:access to courier panel'])->group(function () {
        Route::get('/Courier_Orders', [CourierController::class, 'showOrdersToCourier'])->name('Courier_Orders');
        Route::post('/Courier_Orders/{id}/confirm', [CourierController::class, 'acceptOrder'])->name('courier.confirm');
        Route::post('Courier_Orders/{id}/delivered', [CourierController::class, 'confirmDelivery']) -> name('courier.delivered');
    });

    Route::get('/chats', [ChatController::class, 'index'])
    ->name('chats.index');


Route::get('/chats/open/{userId}', [ChatController::class, 'openChat'])
    ->name('chats.open');


Route::get('/chats/{chat}', [ChatController::class, 'show'])
    ->name('chats.show');

// Отправка сообщения в чат
Route::post('/chats/{chat}/send', [ChatController::class, 'send'])
    ->name('chats.send');

    Route::middleware(['can:access to manager panel'])->group(function () {
        //Route::get('/Edit_menu', function () {
        //    return view('Edit_menu');
        //})->name('Edit_menu');

        Route::get('/courier-assignment', [CourierAssignmentController::class, 'index'])
        ->name('courier.assignment');
        
        Route::post('/orders/{order}/assign-courier', [CourierAssignmentController::class, 'assignCourier'])
        ->name('courier.assign');
        Route::get('/All_Orders/{category?}', [OrderController::class, 'showOrders'])->name('All_Orders');
        Route::post('/orders/{id}/accept', [OrderController::class, 'acceptOrder'])->name('orders.accept');
        Route::post('/orders/{id}/decline', [OrderController::class, 'declineOrder'])->name('orders.decline');

        Route::post('/dish', [DishController::class, 'store'])->name('dish.store');

        Route::get('/Manager_Menu',[DishController::class, 'showDishToManager'])->name('Manager_Menu');

        Route::get('/Edit_menu',[CategoryController::class, 'showCategoriesToCustomer'])->name('Edit_menu');

        Route::get('/dish/{dish}/edit', [DishController::class, 'edit'])->name('dish.edit');

        Route::put('/dish/{dish}', [DishController::class, 'update'])->name('dish.update');

        Route::delete('/dish/delete/{dish}', [DishController::class, 'destroy'])->name('dish.delete');

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
