<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\CSVImportController;
use App\Http\Controllers\Api\ProductOrderController;
use App\Http\Controllers\Api\Auth\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// user register route
Route::post('register', [AuthController::class, 'register']);

// user login route
Route::post('login', [AuthController::class, 'login']);

//user logout
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('orders', OrderController::class);
    
    Route::get('export-product', [ProductController::class, 'exportCSV']);
    Route::post('import-product', [ProductController::class, 'importCSV']);
    Route::post('import-customers', [CustomerController::class, 'importCSV']);
    Route::post('import-orders', [OrderController::class, 'importCSV']);

    Route::get('export-order', [OrderController::class, 'exportCSV']);
    Route::get('export-customer', [CustomerController::class, 'exportCSV']);
    // Route::apiResource('itemsbyOrder', ProductOrderController::class);

});
