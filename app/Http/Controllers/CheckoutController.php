<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\CartItem;

use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = CartItem::with(['product', 'variant'])
            ->where('user_id', auth()->id())
            ->get();

        return view('checkout.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
        ]);

        // SIMPAN DATA KE USER
        if ($request->phone || $request->address) {
            $user = auth()->user();
            if ($request->phone) $user->phone = $request->phone;
            if ($request->address) $user->address = $request->address;
            $user->save();
        }

        if ($request->save_profile == '1') {
            return redirect()->back()->with('success', 'Data berhasil disimpan!');
        }

        if (!auth()->user()->phone || !auth()->user()->address) {
            return back()->with('error', 'Silakan isi data lengkap dulu!');
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $cart = CartItem::with(['product', 'variant'])
            ->where('user_id', auth()->id())
            ->get();

        if ($cart->count() == 0) {
            return back()->with('error', 'Cart kosong');
        }

        $total = 0;
        $itemsData = [];

        foreach ($cart as $item) {
            $price = $item->product->price ?? 0;
            $discount = $item->product->discount_price ?? null;

            if ($item->variant) {
                $price = $item->variant->price ?? $price;
                $discount = $item->variant->discount_price ?? $discount;
            }

            if (!$price || $price == 0) {
                $price = 100000;
            }

            $effectivePrice = $discount ?? $price;
            $subtotal = $effectivePrice * $item->qty;
            $total += $subtotal;

            $itemsData[] = [
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'qty' => $item->qty,
                'price' => $price,
                'discount_price' => $discount,
                'subtotal' => $subtotal,
            ];
        }

        $customerName = $request->customer_name;
        $phone = $request->phone ?? '-';
        $address = $request->address ?? '-';

        if ($request->city) {
            $address .= ', ' . $request->city;
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_name' => $customerName,
            'phone' => $phone,
            'address' => $address,
            'total_price' => $total,
            'status' => 'pending',
        ]);

        foreach ($itemsData as $data) {
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $data['product_id'],
                'variant_id' => $data['variant_id'],
                'qty' => $data['qty'],
                'price' => $data['price'],
                'discount_price' => $data['discount_price'],
                'subtotal' => $data['subtotal'],
            ]);

            if (!empty($data['variant_id'])) {
                ProductVariant::where('id', $data['variant_id'])->decrement('stock', $data['qty']);
            }
        }

        // ✅ ORDER ID UNIQUE DENGAN TIMESTAMP
        $uniqueOrderId = 'ORDER-' . $order->id . '-' . date('YmdHis');

        $params = [
            'transaction_details' => [
                'order_id' => $uniqueOrderId,
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
        $orders = Order::with(['items.product', 'items.variant'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('myorder.index', compact('orders'));
    }

    public function saveProfile(Request $request)
    {
        $request->validate(['name' => 'required']);

        $user = auth()->user();
        $user->name = $request->name;
        if ($request->phone) $user->phone = $request->phone;
        if ($request->address) $user->address = $request->address;
        $user->save();

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function paymentSuccess(Order $order)
    {
        $order->update(['status' => 'success']);
        return response()->json(['success' => true]);
    }

    public function profile()
    {
        return view('profile.index');
    }

    public function profileUpdate(Request $request)
    {
        $request->validate(['name' => 'required']);

        $user = auth()->user();
        $user->name = $request->name;
        if ($request->filled('phone')) $user->phone = $request->phone;
        if ($request->filled('address')) $user->address = $request->address;
        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
