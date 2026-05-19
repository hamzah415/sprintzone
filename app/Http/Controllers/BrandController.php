<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BrandImage; // Import ini sudah benar

class BrandController extends Controller
{
    /**
     * MENAMPILKAN DAFTAR BRAND (Ini yang tadi hilang)
     */
    public function index()
    {
        $brands = Brand::latest()->get();
        return view('brands.index', compact('brands'));

        $brands = Brand::withTrashed()
            ->with(['images', 'creator', 'updater'])
            ->latest()
            ->get();
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // simpan brand
        $brand = Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'created_by' => auth()->id(),
        ]);

        // upload multiple image
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                $path = $image->store('brands', 'public');

                BrandImage::create([
                    'brand_id' => $brand->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()
            ->route('brands.index')
            ->with('success', 'Brand created successfully');
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // update brand
        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'updated_by' => auth()->id(),
        ]);

        // upload gambar baru jika ada
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                $path = $image->store('brands', 'public');

                BrandImage::create([
                    'brand_id' => $brand->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()
            ->route('brands.index')
            ->with('success', 'Brand updated successfully');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return back()->with('success', 'Brand deleted successfully');
    }

    public function restore($id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);

        $brand->restore();

        return back()->with('success', 'Brand restored');
    }
}
