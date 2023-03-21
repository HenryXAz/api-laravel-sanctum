<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

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

Route::post('signUp', [AuthController::class, 'signUp'])->name('signUp');
Route::post('login', [AuthController::class, 'login'])->name('login');





Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::get('profile', [AuthController::class, 'profile'])->name('profile');
  Route::post('logout', [AuthController::class, 'logout'])->name('logout');

  Route::resource('products', ProductController::class)
    ->except(['create', 'edit']);
  Route::get('products/status/en-oferta', [ProductController::class, 'itsOnSale']);

  Route::resource('categories', CategoryController::class)
    ->except(['create', 'edit']);
});
