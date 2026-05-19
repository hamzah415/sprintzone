<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)  // ← TAMBAHKAN Request
    {
        // CEK: Jika stock 0, langsung gagal
        if ($product->stock <= 0) {
            return back()->with('error', 'Stok produk telah habis!');
        }

        // Validasi warna/ukuran wajib kalau produk punya opsi
        if ($product->color && !$request->color) {
            return back()->with('error', 'Pilih warna terlebih dahulu!');
        }

        if ($product->size && !$request->size) {
            return back()->with('error', 'Pilih ukuran terlebih dahulu!');
        }

        // CEK: Sudah ada di cart dengan warna/ukuran yang sama?
        $cart = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('color', $request->color ?? null)
            ->where('size', $request->size ?? null)
            ->first();

        if ($cart) {
            // CEK: Jangan loloskan jika sudah mencapai batas stock
            if ($cart->qty >= $product->stock) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }

            $cart->increment('qty');
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'qty' => 1,
                'color' => $request->color ?? null,
                'size' => $request->size ?? null,
            ]);
        }

        return back()->with('success', 'Ditambahkan ke keranjang');
    }

    public function remove(Product $product)
    {
        CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->delete();

        return back();
    }

    public function increase(Product $product)
    {
        $cart = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            if ($cart->qty >= $product->stock) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }

            $cart->increment('qty');
        }

        return back();
    }

    public function decrease(Product $product)
    {
        $cart = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            if ($cart->qty <= 1) {
                $cart->delete();
            } else {
                $cart->decrement('qty');
            }
        }

        return back();
    }
}