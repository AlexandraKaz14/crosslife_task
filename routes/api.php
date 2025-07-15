<?php

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/catalog', [CatalogController::class, 'catalog']);
Route::post('/create-order', [OrderController::class, 'createOrder']);
Route::post('/approve-order', [OrderController::class, 'approveOrder']);

