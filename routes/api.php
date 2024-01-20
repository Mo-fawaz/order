<?php

use App\Http\Controllers\API\CityController;
use App\Http\Controllers\API\CountryController;
use App\Http\Controllers\api\FoodController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\RestaurantController;
use App\Http\Controllers\API\TypeController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login',[UserController::class,'login']);
Route::post('regester',[UserController::class,'store']);

Route::prefix('country')->group(function () {
    Route::get('index', [CountryController::class, 'index']);
    Route::get('show/{uuid}', [CountryController::class, 'show']);
    Route::post('add', [CountryController::class, 'store']);
    Route::delete('delete/{uuid}', [CountryController::class, 'destroy']);
})->middleware('auth:sanctum');

Route::prefix('city')->group(function () {
    Route::get('index', [CityController::class, 'index']);
    Route::get('show/{uuid}', [CityController::class, 'show']);
    Route::post('add', [CityController::class, 'store']);
    Route::delete('delete/{uuid}', [CityController::class, 'destroy']);
})->middleware('auth:sanctum');
Route::prefix('type')->group(function () {
    Route::get('index', [TypeController::class, 'index']);
    Route::get('show/{uuid}', [TypeController::class, 'show']);
    Route::post('add', [TypeController::class, 'store']);
    Route::delete('delete/{uuid}', [TypeController::class, 'destroy']);
})->middleware('auth:sanctum');
Route::prefix('restaurant')->group(function () {
    Route::get('index', [RestaurantController::class, 'index']);
    Route::get('show/{uuid}', [RestaurantController::class, 'show']);
    Route::post('add', [RestaurantController::class, 'store']);
    Route::post('update/{uuid}', [RestaurantController::class, 'update']);
    Route::delete('delete/{uuid}', [RestaurantController::class, 'destroy']);
})->middleware('auth:sanctum');
Route::prefix('food')->group(function () {
    Route::get('index', [FoodController::class, 'index']);
    Route::get('show/{uuid}', [FoodController::class, 'show']);
    Route::post('add', [FoodController::class, 'store']);
    Route::delete('delete/{uuid}', [FoodController::class, 'destroy']);
})->middleware('auth:sanctum');
Route::prefix('order')->group(function () {
    Route::get('index', [OrderController::class, 'index']);
    Route::get('show/{uuid}', [OrderController::class, 'show']);
    Route::post('add', [OrderController::class, 'store']);
    Route::delete('delete/{uuid}', [OrderController::class, 'destroy']);
})->middleware('auth:sanctum');