<?php

use App\Http\Controllers\Api\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/catalog', [CatalogController::class, 'catalog']);

