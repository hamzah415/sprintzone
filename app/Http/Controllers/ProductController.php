<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with([
            'category',
            'brand',
            'creator',
            'updater',
            'variants'
        ])->latest()->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Saat CREATE - ONLY created_by, updated_by NULL
        $validated['created_by'] = Auth::id();
        // $validated['updated_by'] = null; // DIHAPUS atau NULL
        $validated['stock'] = 0;
        $validated['price'] = 0;

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload image baru jika ada
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Saat UPDATE - baru diisi updated_by
        $validated['updated_by'] = Auth::id();

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product = Product::with(['category', 'brand', 'variants'])->findOrFail($product->id);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product = Product::with(['category', 'brand', 'variants'])->findOrFail($product->id);

        return view('products.edit', compact('product'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Hapus gambar jika ada
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Hapus variant gambar
        foreach ($product->variants as $variant) {
            if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                Storage::disk('public')->delete($variant->image);
            }
        }

        // Hapus product (variant akan kehapus otomatis karena onDelete cascade)
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');
    }
}
