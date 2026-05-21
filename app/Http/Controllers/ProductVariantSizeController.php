<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariantSize;

class ProductVariantSizeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'variant_id' => 'required',
            'size' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        ProductVariantSize::create([
            'variant_id' => $request->variant_id,
            'size' => $request->size,
            'stock' => $request->stock,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Size ditambahkan!');
    }

    public function update(Request $request, ProductVariantSize $variantSize)
    {
        $request->validate([
            'size' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        $variantSize->update([
            'size' => $request->size,
            'stock' => $request->stock,
            'updated_by' => auth()->id(),
        ]);

        return back()->with('success', 'Size diperbarui!');
    }

    public function destroy(ProductVariantSize $variantSize)
    {
        $variantSize->delete();
        return back()->with('success', 'Size dihapus!');
    }
}
