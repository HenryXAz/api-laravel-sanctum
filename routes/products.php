<?php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function() {
  Route::resource('products', ProductController::class)
    ->except(['create', 'edit']);
});