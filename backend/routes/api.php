<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelOrders\TravelOrdersController;
use App\Http\Controllers\TravelOrders\TravelOrderStatusController;
use App\Http\Controllers\Locations\CitiesController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\TravelTypes\TravelTypeController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Rotas de Travel Orders
    Route::prefix('travel_order')->group(function () {
        Route::post('create', [TravelOrdersController::class, 'create']);
        Route::get('list', [TravelOrdersController::class, 'list']);
        Route::get('getStatistics', [TravelOrdersController::class, 'getStatistics']);
        Route::put('update_status/{id}', [TravelOrdersController::class, 'updateStatus']);
        Route::delete('delete/{id}', [TravelOrdersController::class, 'delete']);
    });

    // Cities
    Route::prefix('cities')->group(function () {
        Route::get('list', [CitiesController::class, 'list']);
    });

    // Travel Types
    Route::prefix('travel_types')->group(function () {
        Route::get('list', [TravelTypeController::class, 'list']);
    });

    // Travel Order Status
    Route::prefix('travel_order_status')->group(function () {
        Route::get('list', [TravelOrderStatusController::class, 'list']);
    });

      // Notifications
      Route::prefix('notification')->group(function () {
        Route::get('list', [NotificationController::class, 'list']);
        Route::delete('discard/{id}', [NotificationController::class, 'discard']);
    });
});


require __DIR__.'/auth.php';


