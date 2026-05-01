<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PosController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse The JSON response containing the paginated list of categories and products.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        if ($request->has('search')) {
            $input = $request->input('search');

            $query->where(function ($q) use ($input) {
                $q->where('name', 'LIKE', "%{$input}%")
                    ->orWhere('code', 'LIKE', "%{$input}%")
                    ->orWhere('barcode', 'LIKE', "%{$input}%")
                    ->orWhere('description', 'LIKE', "%{$input}%");
            });
        }

        $products = $query->latest()->paginate(20);

        return $this->success($products, 'Products retrieved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id The ID of the category to be retrieved.
     * @return JsonResponse The JSON response containing the details of the specified category, along with any relevant messages or errors.
     */
    public function show($id): JsonResponse
    {
        $product = Product::where('code', $id)->orWhere('barcode', $id)->firstOrFail();

        return $this->success($product, 'Product retrieved successfully.');
    }
}
