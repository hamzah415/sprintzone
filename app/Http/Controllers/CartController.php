<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = CartItem::with(['product', 'variant'])
            ->where('user_id', auth()->id())
            ->get();

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        // variant_id wajib kalau produk punya variant
        $variantId = $request->variant_id;
        
        if ($product->variants->count() > 0 && !$variantId) {
            return back()->with('error', 'Pilih warna dan ukuran terlebih dahulu!');
        }

        // Ambil data variant jika ada
        $variant = null;
        $stock = $product->stock ?? 0;
        $color = null;
        $size = null;

        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if ($variant) {
                $stock = $variant->stock;
                $color = $variant->color;
                $size = $variant->size;
            }
        }

        // CEK: Stok 0
        if ($stock <= 0) {
            return back()->with('error', 'Stok produk telah habis!');
        }

        // CEK: Sudah ada di cart dengan variant yang sama?
        $existingCart = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('variant_id', $variantId ?? null)
            ->first();

        if ($existingCart) {
            // CEK: Jangan loloskan jika sudah mencapai batas stock
            if ($existingCart->qty >= $stock) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }

            // Tambah qty, tapi maximal hingga stock
            $newQty = min($existingCart->qty + ($request->qty ?? 1), $stock);
            $existingCart->update(['qty' => $newQty]);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'variant_id' => $variantId,
                'qty' => $request->qty ?? 1,
            ]);
        }

        return back()->with('success', 'Ditambahkan ke keranjang');
    }

    public function remove(CartItem $cartItem)
    {
        // Pastikan user owns this cart item
        if ($cartItem->user_id == auth()->id()) {
            $cartItem->delete();
        }

        return back();
    }

    public function increase(CartItem $cartItem)
    {
        if ($cartItem->user_id != auth()->id()) {
            return back();
        }

        // Ambil stock dari variant atau produk
        $maxStock = $cartItem->variant?->stock ?? $cartItem->product->stock ?? 0;

        if ($cartItem->qty >= $maxStock) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        $cartItem->increment('qty');

        return back();
    }

    public function decrease(CartItem $cartItem)
    {
        if ($cartItem->user_id != auth()->id()) {
            return back();
        }

        if ($cartItem->qty <= 1) {
            $cartItem->delete();
        } else {
            $cartItem->decrement('qty');
        }

        return back();
    }
}