<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => "auth:sanctum"], function() {
  Route::get('profile', [AuthController::class, 'profile']);  
  Route::get('logout', [AuthController::class, 'logout']);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('signUp', [AuthController::class, 'signUp']);