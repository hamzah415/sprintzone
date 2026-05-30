<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories,name',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        // UPLOAD IMAGE
        if ($request->hasFile('image')) {

            $imagePath = $request->file('image')
                ->store('categories', 'public');
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $imagePath,
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Kategori Berhasil Ditambah!');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $category->image;

        // UPDATE IMAGE
        if ($request->hasFile('image')) {

            // DELETE OLD IMAGE
            if ($category->image) {

                Storage::disk('public')
                    ->delete($category->image);
            }

            $imagePath = $request->file('image')
                ->store('categories', 'public');
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $imagePath,
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Kategori Berhasil Diperbarui!');
    }

    public function destroy(Category $category)
    {
        // DELETE IMAGE
        if ($category->image) {

            Storage::disk('public')
                ->delete($category->image);
        }

        $category->delete();

        return back()->with('success', 'Category deleted');
    }
}