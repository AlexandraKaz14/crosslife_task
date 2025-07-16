<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogManager
{
    public function getCatalog(Request $request)
    {
        $query = Product::select('id', 'name', 'description', 'price', 'stock');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }
        if ($request->filled('price_from')) {
            $query->where('price', '>=', (float) $request->price_from);
        }
        if ($request->filled('price_to')) {
            $query->where('price', '<=', (float) $request->price_to);
        }

        if ($request->filled('sort_by') && in_array($request->sort_by, ['price'])) {
            $direction = $request->get('sort_direction', 'asc') === 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sort_by, $direction);
        } else {
            $query->orderBy('name');
        }

        return $query->paginate(50);

    }

}
