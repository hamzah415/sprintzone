<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\CartItem;

use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        return view('checkout.index', compact('cart'));
    }

    public function store(Request $request)
    {
        // Validasi minimal
        $request->validate([
            'customer_name' => 'required',
        ]);

        // SIMPAN DATA KE USER
        if ($request->phone || $request->address) {
            $user = auth()->user();

            if ($request->phone) {
                $user->phone = $request->phone;
            }

            if ($request->address) {
                $user->address = $request->address;
            }

            $user->save();
        }

        // JIKA TOMBOL SIMPAN DATA DITEKAN
        if ($request->save_profile == '1') {
            return redirect()->back()->with('success', 'Data berhasil disimpan!');
        }

        // CEK DATA LENGKAP ?
        if (!auth()->user()->phone || !auth()->user()->address) {
            return back()->with('error', 'Silakan isi data lengkap dulu!');
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $cart = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($cart->count() == 0) {
            return back()->with('error', 'Cart kosong');
        }

        $total = 0;

        foreach ($cart as $item) {
            $price = $item->product->discount_price ?? $item->product->price;
            $total += $price * $item->qty;
        }

        //AMBIL DARI FORM (BUKAN DARI auth()->user())
        $customerName = $request->customer_name;
        $phone = $request->phone ?? '-';
        $address = $request->address;

        // Gabungkan city ke address jika ada
        if ($request->city) {
            $address .= ', ' . $request->city;
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_name' => $customerName,
            'phone' => $phone,
            'address' => $address ?? '-',
            'total_price' => $total,
            'status' => 'pending',
        ]);

        foreach ($cart as $item) {
            $price = $item->product->discount_price ?? $item->product->price;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'price' => $price,
                'qty' => $item->qty,
                'subtotal' => $price * $item->qty,
            ]);
        }

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $order->id,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $customerName,
                'email' => auth()->user()->email,
                'phone' => $phone,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $order->update([
            'snap_token' => $snapToken,
        ]);

        CartItem::where('user_id', auth()->id())->delete();

        return view('checkout.payment', compact('snapToken', 'order'));
    }

    public function myorder()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('myorder.index', compact('orders'));
    }

    public function saveProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $user = auth()->user();
        $user->name = $request->name;

        if ($request->phone) {
            $user->phone = $request->phone;
        }

        if ($request->address) {
            $user->address = $request->address;
        }

        $user->save();

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function paymentSuccess(Order $order)
    {
        $order->update([
            'status' => 'success'
        ]);

        foreach ($order->items as $item) {
            $item->product->decrement('stock', $item->qty);
        }

        CartItem::where('user_id', $order->user_id)->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function profile()
    {
        return view('profile.index');
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $user = auth()->user();
        $user->name = $request->name;

        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->filled('address')) {
            $user->address = $request->address;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
