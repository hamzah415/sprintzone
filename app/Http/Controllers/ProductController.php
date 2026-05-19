<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Brand;
use App\Models\Category;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::with([
            'category',
            'brand',
            'company'
        ])->latest()->get();

        $categories = Category::all();

        $brands = Brand::all();

        $companies = Company::all();

        return view('products.index', compact(
            'products',
            'categories',
            'brands',
            'companies'
        ));
    }

    public function create()
    {
        $companies = Company::where('is_active', true)->get();

        $brands = Brand::all();

        $categories = Category::all();

        return view('products.create', compact(
            'companies',
            'brands',
            'categories'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'sku' => 'nullable|max:100',

            'description' => 'nullable',

            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',

            'stock' => 'required|integer',
            'weight' => 'nullable|numeric',

            'company_id' => 'nullable|exists:companies,id',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'status' => 'required'
        ]);

        // Upload image
        if ($request->hasFile('image')) {

            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'sku' => 'nullable|max:100',
            'description' => 'nullable',

            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',

            'stock' => 'required|integer',
            'weight' => 'nullable|numeric',

            'company_id' => 'nullable|exists:companies,id',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'status' => 'required'
        ]);

        // upload image baru
        if ($request->hasFile('image')) {

            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        $product = Product::with('brand', 'category')->findOrFail($id);

        return view('products.show', compact('product'));
    }
}
