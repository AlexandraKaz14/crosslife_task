<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CatalogManager;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    protected CatalogManager $manager;

    public function __construct(CatalogManager $manager)
    {
        $this->manager = $manager;
    }

    public function catalog(Request $request)
    {
        $products = $this->manager->getCatalog($request);

        return response()->json($products);
    }

}
