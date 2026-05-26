<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\URegisterController;
use App\Http\Controllers\Auth\RecoveryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BrandCategoryReportController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryReportController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\UserReportController;

/*
|--------------------------------------------------------------------------
*/

// =========================================================== // 1. HALAMAN PUBLIK (Guest - Tanpa Login) // ===========================================================

Route::get('/', function () {
    $products = Product::with('brand')->where('status', 'active')->latest()->get();
    return view('welcome', compact('products'));
})->name('welcome');

Route::get('/login', function () {
    if (auth()->check()) {
        return redirect()->route('welcome');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required'],]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect('/dashboard');
        }
        return redirect('/');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
})->name('login.store');

// Shop/Etalase 
Route::get('/etalase', function () {
    $query = \App\Models\Product::with('brand')->where('status', 'active');

    // Filter brand
    if (request()->has('brand')) {
        $brand = \App\Models\Brand::where('slug', request('brand'))->first();
        if ($brand) {
            $query->where('brand_id', $brand->id);
        }
    }

    // Sort
    $sort = request('sort', 'latest');

    if ($sort == 'price_low') {
        $query->withMin('variants', 'price')->orderBy('variants_min_price', 'asc');
    } elseif ($sort == 'price_high') {
        $query->withMax('variants', 'price')->orderBy('variants_max_price', 'desc');
    } else {
        $query->latest();
    }

    $products = $query->paginate(12);

    return view('products.etalase', compact('products'));
})->name('products.etalase');

Route::get('/etalase/{id}', function ($id) {
    $product = Product::with('brand')->findOrFail($id);
    return view('products.show', compact('product'));
})->name('products.show');

Route::get('/brand', function () {
    return view('brand');
})->name('brand');

// Search Page (hasil lengkap)
Route::get('/search', function () {
    $query = request('q');

    if (empty($query)) {
        return redirect('/');
    }

    $products = \App\Models\Product::with(['brand', 'variants'])
        ->where('status', 'active')
        ->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhereHas('brand', function ($b) use ($query) {
                    $b->where('name', 'like', "%{$query}%");
                });
        })
        ->latest()
        ->paginate(12);

    return view('search', compact('products', 'query'));
})->name('search');

// Search Page (hasil lengkap)
Route::get('/search', function () {
    $query = request('q');

    if (empty($query)) {
        return redirect('/');
    }

    $products = \App\Models\Product::with('brand')
        ->where('status', 'active')
        ->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhereHas('brand', function ($b) use ($query) {
                    $b->where('name', 'like', "%{$query}%");
                });
        })
        ->latest()
        ->paginate(12);

    return view('search', compact('products', 'query'));
})->name('search');

// Form Registrasi 
Route::get('/uregister', [URegisterController::class, 'showRegistrationForm'])->name('uregister');
Route::post('/uregister', [URegisterController::class, 'register'])->name('uregister.store');

// Google Auth 
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// routes/web.php
Route::post('/profile/save', [CheckoutController::class, 'saveProfile'])->name('user.profile.save');

// =========================================================== // 2. 2FA SETUP & VERIFY (Bisa diawalkan / Sebelum login) // ===========================================================

Route::middleware(['web'])->group(function () {
    Route::get('/2fa/setup', [TwoFactorController::class, 'showSetupForm'])->name('2fa.setup');
    Route::post('/2fa/setup', [TwoFactorController::class, 'finishSetup'])->name('2fa.setup.post');
    Route::get('/2fa/verify', [TwoFactorController::class, 'showVerifyForm'])->name('2fa.verify');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verifyOtp'])->name('2fa.post');
    // FIX: Pindahkan dari GoogleController ke TwoFactorController
    Route::post('/2fa/cancel', [TwoFactorController::class, 'cancel2fa'])->name('2fa.cancel');

    // 2FA Recovery
    Route::get('/recovery-2fa', [RecoveryController::class, 'redirect'])->name('2fa.recovery');
    Route::get('/recovery-2fa/callback', [RecoveryController::class, 'callback']);
});

// =========================================================== // 3. FITUR USER (Wajib Login Saja) // ===========================================================

Route::middleware(['auth'])->group(function () {
    // Profile routes (dalam group auth)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Cart (FIX: Hanya define sekali, tidak ada duplikasi)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    // routes/web.php - Cart route
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/{cartItem}/increase', [CartController::class, 'increase'])->name('cart.increase');
    Route::post('/cart/{cartItem}/decrease', [CartController::class, 'decrease'])->name('cart.decrease');
    Route::delete('/cart/{cartItem}/remove', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Purchase History
    Route::get('/My-Order', [CheckoutController::class, 'myorder'])->name('myorder.index');

    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        session()->forget(['google2fa_valid', '2fa_passed']);
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('welcome');
    })->name('logout');
});

// =========================================================== // 4. ADMIN & DASHBOARD (Wajib Login + 2FA Lolos + isAdmin) // ===========================================================

Route::middleware(['auth', '2fa', 'isAdmin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('companies', CompanyController::class)->only(['index', 'store']);

    Route::resource('brands', BrandController::class)->only(['index', 'store']);
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
    Route::put('/brands/{id}/restore', [BrandController::class, 'restore'])->name('brands.restore');

    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/product-detail', function () {
        return view('product-detail');
    })->name('product.detail');

    // ===========================================================
    // 5. ADMIN ONLY (Deep Admin Access)
    // ===========================================================

    Route::middleware(['isAdmin'])->group(function () {

        // Products
        Route::resource('products', ProductController::class)->except(['show']);

        // 1. Store - POST
        Route::post('/variants', [ProductVariantController::class, 'store'])->name('variants.store');

        // 2. Batch Delete - DELETE (HARUS di atas route {variant})
        Route::delete('/variants/batch-delete', [ProductVariantController::class, 'batchDelete'])->name('variants.batchDelete');

        // 3. Update - PUT
        Route::put('/variants/{variant}', [ProductVariantController::class, 'update'])->name('variants.update');

        // 4. Delete - DELETE
        Route::delete('/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');

        // Panel Admin
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        // Users Management
        Route::get('/admin/users', [UserController::class, 'index']);
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('users', UserController::class)->only(['index', 'store', 'update']);
        });

        Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])
            ->name('companies.destroy');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('users', UserController::class);
        });

        Route::get('/purchase-history', [PurchaseController::class, 'index'])->name('purchase.history');
        Route::get('/purchase-history/{order}', [PurchaseController::class, 'show'])->name('purchase.show');
        Route::put('/purchase-history/{order}/status', [PurchaseController::class, 'updateStatus'])->name('purchase.status');

        // Report 
        Route::get('/laporan/penjualan', [SalesReportController::class, 'index'])->name('laporan.penjualan');
        Route::post('/laporan/penjualan', [SalesReportController::class, 'report'])->name('laporan.penjualan.report');
        Route::get('/laporan/penjualan/export', [SalesReportController::class, 'export'])->name('laporan.penjualan.export');

        Route::get('/laporan/stok', [StockReportController::class, 'index'])->name('laporan.stok');
        Route::get('/laporan/stok/export', [StockReportController::class, 'export'])->name('laporan.stok.export');

        Route::get('/laporan/user', [UserReportController::class, 'index'])->name('laporan.user');
        Route::get('/laporan/user/export', [UserReportController::class, 'export'])->name('laporan.user.export');

        Route::get('/laporan/brand-kategori', [BrandCategoryReportController::class, 'index'])->name('laporan.brand-kategori');
        Route::get('/laporan/brand-kategori/export', [BrandCategoryReportController::class, 'export'])->name('laporan.brand-kategori.export');
    });

    // Transaction (Admin bisa melihat history juga)
    Route::get('/admin/purchase-history', [PurchaseController::class, 'index'])->name('admin.purchase.history');
});

// =========================================================== // 6. PAYMENT CALLBACK (Tanpa Middleware - Webhook) // ===========================================================

Route::post('/payment/success/{order}', [CheckoutController::class, 'paymentSuccess'])->name('payment.success');

// routes/web.php - TAMBAHIN
Route::get('/test-variant', function () {
    $sizesInput = '36, 37, 38';
    $sizesArray = explode(',', $sizesInput);
    $sizesArray = array_map('trim', $sizesArray);
    $sizesArray = array_filter($sizesArray, function ($v) {
        return !empty($v);
    });

    dd([
        'input' => $sizesInput,
        'parsed' => $sizesArray,
        'count' => count($sizesArray)
    ]);
});
