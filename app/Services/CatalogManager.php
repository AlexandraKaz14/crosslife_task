<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogManager
{
    public function getCatalog(Request $request)
    {
        $query = Product::select('id', 'name', 'description', 'price', 'stock')
            ->when($request->filled('name'), fn($q) => $q->where('name', 'like', '%' . $request->name . '%')
            )
            ->when($request->filled('description'), fn($q) => $q->where('description', 'like', '%' . $request->description . '%')
            )
            ->when($request->filled('price_from'), fn($q) => $q->where('price', '>=', (float)$request->price_from)
            )
            ->when($request->filled('price_to'), fn($q) => $q->where('price', '<=', (float)$request->price_to)
            )
            ->when(
                $request->filled('sort_by') && in_array($request->sort_by, ['price']),
                fn($q) => $q->orderBy(
                    $request->sort_by,
                    $request->get('sort_direction', 'asc') === 'desc' ? 'desc' : 'asc'
                ),
                fn($q) => $q->orderBy('name')
            );

        return $query->paginate(50);

    }

}
