<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/**
 * Product Routes
 *
 * All routes related to product management
 */
Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
});
