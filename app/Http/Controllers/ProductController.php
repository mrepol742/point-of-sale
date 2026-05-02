<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends ApiController
{
    /**
     * Display a listing of the products.
     *
     * @return JsonResponse The JSON response containing the list of products, along with any relevant messages or errors.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()->with(['user', 'category']);

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
     * Store a newly created product in storage.
     *
     * @param StoreProductRequest $request The validated request containing the data for the new product.
     * @return JsonResponse The JSON response indicating the success or failure of the product creation process, along with any relevant messages or errors.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_ulid'] = auth()->user()->ulid;

        $imagePath = null;

        DB::beginTransaction();

        try {
            // Generate barcode if missing
            if (is_null($validated['barcode'])) {
                $lastProduct = Product::orderBy('id', 'desc')->first();

                if ($lastProduct && $lastProduct->barcode) {
                    $lastBarcode = ltrim($lastProduct->barcode, '0');
                    $nextBarcode = str_pad((int) $lastBarcode + 1, 9, '0', STR_PAD_LEFT);
                } else {
                    $nextBarcode = '000000001';
                }

                $validated['barcode'] = $nextBarcode;
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('product_images', 'public');
                $validated['image'] = $imagePath;
            }

            $product = Product::create($validated);

            DB::commit();

            return $this->success($product, 'Product created successfully.', 201);
        } catch (\Throwable $e) {
            Log::error('Failed to create product: ' . $e->getMessage());

            DB::rollBack();

            // Clean up uploaded file if transaction fails
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            return $this->error('Failed to create product.', 500);
        }
    }

    /**
     * Update the specified product in storage.
     *
     * @param UpdateProductRequest $request The validated request containing the data for updating the product.
     * @param Product $product The product instance to be updated.
     * @return JsonResponse The JSON response indicating the success or failure of the product update process, along with any relevant messages or errors.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $validated = $request->validated();
        $imagePath = null;
        $oldImage = $product->image;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('product_images', 'public');
                $validated['image'] = $imagePath;
            }

            $product->update($validated);

            DB::commit();

            // delete old image AFTER successful commit
            if ($imagePath && $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            return $this->success($product, 'Product updated successfully.');
        } catch (\Throwable $e) {
            Log::error('Failed to update product: ' . $e->getMessage());

            DB::rollBack();

            // delete newly uploaded image if transaction failed
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            return $this->error('Failed to update product.', 500);
        }
    }

    /**
     * Display the specified product by code or barcode.
     *
     * @return JsonResponse The JSON response containing the product details, along with any relevant messages or errors.
     */
    public function show($id): JsonResponse
    {
        $product = Product::where('code', $id)->orWhere('barcode', $id)->firstOrFail();

        return $this->success($product, 'Product retrieved successfully.');
    }

    /**
     * Remove the specified product from storage.
     *
     * @param Product $product The product instance to be deleted.
     * @return JsonResponse The JSON response indicating the success or failure of the product deletion process, along with any relevant messages or errors.
     */
    public function delete(Product $product): JsonResponse
    {
        $product->delete();

        return $this->success($product, 'Product deleted successfully.');
    }
}
