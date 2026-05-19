<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductVariantController extends Controller
{
    /**
     * Store Variant
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id'      => 'required|exists:products,id',
            'color'           => 'nullable|string|max:100',
            'size'            => 'nullable|string|max:50',
            'sku'             => 'nullable|string|max:255',
            'price'           => 'required|numeric',
            'discount_price'  => 'nullable|numeric',
            'stock'           => 'required|integer|min:0',
            'weight'          => 'nullable|integer',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('image');

        /**
         * Upload image
         */
        if ($request->hasFile('image')) {

            $data['image'] = $request->file('image')
                ->store('variants', 'public');
        }

        /**
         * Create variant
         */
        ProductVariant::create($data);

        return back()->with(
            'success',
            'Product variant created successfully.'
        );
    }

    /**
     * Update Variant
     */
    public function update(Request $request, ProductVariant $variant)
    {
        $request->validate([
            'product_id'      => 'required|exists:products,id',
            'color'           => 'nullable|string|max:100',
            'size'            => 'nullable|string|max:50',
            'sku'             => 'nullable|string|max:255',
            'price'           => 'required|numeric',
            'discount_price'  => 'nullable|numeric',
            'stock'           => 'required|integer|min:0',
            'weight'          => 'nullable|integer',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('image');

        /**
         * Upload new image
         */
        if ($request->hasFile('image')) {

            /**
             * Delete old image
             */
            if ($variant->image &&
                Storage::disk('public')->exists($variant->image)) {

                Storage::disk('public')
                    ->delete($variant->image);
            }

            /**
             * Store new image
             */
            $data['image'] = $request->file('image')
                ->store('variants', 'public');
        }

        /**
         * Update variant
         */
        $variant->update($data);

        return back()->with(
            'success',
            'Product variant updated successfully.'
        );
    }

    /**
     * Delete Variant
     */
    public function destroy(ProductVariant $variant)
    {
        /**
         * Delete image
         */
        if ($variant->image &&
            Storage::disk('public')->exists($variant->image)) {

            Storage::disk('public')
                ->delete($variant->image);
        }

        /**
         * Delete variant
         */
        $variant->delete();

        return back()->with(
            'success',
            'Product variant deleted successfully.'
        );
    }
}