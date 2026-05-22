<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductVariantController extends Controller
{
    /**
     * Store Variant (Multiple Sizes)
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id'      => 'required|exists:products,id',
            'color'           => 'nullable|string|max:100',
            'sizes'           => 'required|string|max:255',
            'sku'             => 'nullable|string|max:255',
            'price'           => 'required|numeric',
            'discount_price'  => 'nullable|numeric',
            'stock'           => 'required|integer|min:0',
            'weight'          => 'nullable|integer',
            'image'           => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $product = Product::find($request->product_id);

        // Parse sizes
        $sizes = array_map('trim', explode(',', strtoupper($request->sizes)));
        $sizes = array_filter($sizes);

        if (empty($sizes)) {
            return back()->with('error', 'Masukkan minimal 1 size!');
        }

        // Upload image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('variants', 'public');
        }

        // SKU base
        $skuBase = $request->sku ?? strtoupper(substr($request->color ?? 'CL', 0, 3)) . '-' . strtoupper(substr($product->name ?? 'PRD', 0, 3));

        // Create variant untuk setiap size
        $created = 0;
        foreach ($sizes as $index => $size) {
            $sku = $skuBase . '-' . $size . '-' . ($index + 1);

            ProductVariant::create([
                'product_id' => $request->product_id,
                'color' => strtoupper($request->color),
                'size' => $size,
                'sku' => $sku,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'stock' => $request->stock,
                'weight' => $request->weight,
                'image' => $imagePath,
                'created_by' => Auth::id(),
                // updated_by = null saat create
            ]);
            $created++;
        }

        return back()->with('success', "$created variant berhasil dibuat!");
    }

    /**
     * Update Variant
     */
    public function update(Request $request, ProductVariant $variant)
    {
        $request->validate([
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
        $data['updated_by'] = Auth::id();  // Baru diisi saat update

        if ($request->hasFile('image')) {
            if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                Storage::disk('public')->delete($variant->image);
            }
            $data['image'] = $request->file('image')->store('variants', 'public');
        }

        $variant->update($data);

        return back()->with('success', 'Variant berhasil diupdate!');
    }

    /**
     * Delete Variant
     */
    public function destroy(ProductVariant $variant)
    {
        if ($variant->image && Storage::disk('public')->exists($variant->image)) {
            Storage::disk('public')->delete($variant->image);
        }

        $variant->delete();

        return back()->with('success', 'Variant berhasil dihapus!');
    }
}
