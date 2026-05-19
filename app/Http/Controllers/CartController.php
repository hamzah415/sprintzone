<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        return view('cart.index', compact('cart'));
    }

    public function add(Product $product)
    {
        // CEK: Jika stock 0, langsung gagal
        if ($product->stock <= 0) {
            return back()->with('error', 'Stok produk telah habis!');
        }

        $cart = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
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
            ]);
        }

        return back()->with('success', 'Added to cart');
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
            // CEK: Jangan tambahkan jika sudah mencapai batas stock
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