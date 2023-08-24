<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function() {
  Route::resource('categories', CategoryController::class)
    ->except(['edit', 'create']);
});