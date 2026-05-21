<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantSize;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = CartItem::with(['product', 'variant', 'size'])
            ->where('user_id', auth()->id())
            ->get();

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $variantId = $request->variant_id;
        $sizeId = $request->size_id;

        // Variant wajib kalau produk punya variant
        if ($product->variants->count() > 0 && !$variantId) {
            return back()->with('error', 'Pilih warna dan ukuran terlebih dahulu!');
        }

        // Ambil data variant & size
        $variant = null;
        $size = null;
        $stock = $product->stock ?? 0;
        $color = null;

        if ($variantId) {
            $variant = ProductVariant::find($variantId);

            if ($variant) {
                $color = $variant->color;

                // CEK: Kalau ada size_id, ambil stock dari sizes table
                if ($sizeId) {
                    $size = ProductVariantSize::find($sizeId);
                    if ($size && $size->variant_id == $variantId) {
                        $stock = $size->stock;
                    }
                } else {
                    // Kalau ga ada size_id, fallback ke variant stock (legacy)
                    $stock = $variant->stock ?? 0;
                }
            }
        }

        // CEK: Stok 0
        if ($stock <= 0) {
            return back()->with('error', 'Stok produk telah habis!');
        }

        // CEK: Sudah ada di cart dengan variant + size yang sama?
        $existingCart = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('variant_id', $variantId ?? null)
            ->where('size_id', $sizeId ?? null)
            ->first();

        if ($existingCart) {
            // CEK: Jangan loloskan jika sudah mencapai batas stock
            if ($existingCart->qty >= $stock) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }

            // Tambah qty
            $newQty = min($existingCart->qty + ($request->qty ?? 1), $stock);
            $existingCart->update(['qty' => $newQty]);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'variant_id' => $variantId,
                'size_id' => $sizeId,
                'qty' => $request->qty ?? 1,
            ]);
        }

        return back()->with('success', 'Ditambahkan ke keranjang');
    }

    public function remove(CartItem $cartItem)
    {
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

        $maxStock = 0;

        if ($cartItem->size) {
            $maxStock = $cartItem->size->stock;
        } elseif ($cartItem->variant) {
            $maxStock = $cartItem->variant->stock;
        } else {
            $maxStock = $cartItem->product->stock ?? 0;
        }

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
