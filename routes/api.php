<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DrinkController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

//Public Routes

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/drinks', [DrinkController::class, 'index']);
Route::get('/drinks/{drink}', [DrinkController::class, 'show']);


//Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //Category
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::post('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    //Drink
    Route::post('/drinks', [DrinkController::class, 'store']);
    Route::post('/drinks/{drink}', [DrinkController::class, 'update']);
    Route::delete('/drinks/{drink}', [DrinkController::class, 'destroy']);

    //Order
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::get('/my-orders', [OrderController::class, 'myOrders']);
});

