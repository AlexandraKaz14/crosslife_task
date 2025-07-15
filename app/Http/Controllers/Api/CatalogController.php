<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function catalog()
    {
        $products = Product::select('id', 'name', 'description', 'price', 'stock')->orderBy('name')
            ->paginate(50);
        return response()->json($products);
    }
}
