<?php

use Illuminate\Support\Facades\Route;
use Mews\Captcha\Captcha;

// Admin Controllers
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\PaymentWalletController;
use App\Http\Controllers\Admin\PayoutWalletOptionController;
use App\Http\Controllers\Admin\WalletDepositApprovalController;

// Auth Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserDashboardController;

// Customer / App Controllers
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPayoutWalletController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorDeliveryAddressController;
use App\Http\Controllers\VendorProductController;
use App\Http\Controllers\VendorShopController;
use App\Http\Controllers\WalletDepositController;

/*
|--------------------------------------------------------------------------
| NEW LANGUAGE SWITCH (Google Translate)
|--------------------------------------------------------------------------
*/

// Route::post('/language-switch', function (\Illuminate\Http\Request $request) {

//     $locale = $request->input('locale');
//     $available = ['en', 'zh', 'fr', 'es'];

//     if (! in_array($locale, $available, true)) {
//         $locale = 'en';
//     }

//     session(['app_locale' => $locale]);
//     app()->setLocale($locale);

//     return back();
// })->name('lang.switch');


Route::post('/language', [LanguageController::class, 'switch'])
    ->name('lang.switch');

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'index'])->name('home');

Route::view('/shop', 'shop')->name('shop');
Route::view('/cart', 'cart')->name('cart');

// All Products
// Route::get('/products/shop', [PageController::class, 'shop'])->name('page.products.shop');

// View all products
Route::get('/shop/products', [PageController::class, 'shop'])
    ->name('page.products.shop');

// Filter by category (slug)
Route::get('/products/category/{categorySlug}', [PageController::class, 'shop'])
    ->name('page.products.category');


// Product Details
Route::get('/product/{slug}', [PageController::class, 'product'])
    ->name('page.products.show');


/*
|--------------------------------------------------------------------------
| CART + CHECKOUT
|--------------------------------------------------------------------------
*/

Route::prefix('cart')->group(function () {

    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('update', [CartController::class, 'update'])->name('cart.update');
    Route::post('remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('add-ajax', [CartController::class, 'addAjax'])->name('cart.add.ajax');
    Route::get('shopping', [CartController::class, 'viewCart'])->name('cart.view');
    Route::get('wishlist', [CartController::class, 'wishlist'])->name('wishlist.summary');
    Route::post('update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');

    // Checkout
    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});

// AJAX Cart Summary
Route::get('/cart/summary', function () {
    return response()->json([
        'cart_count' => \Cart::getContent()->sum('quantity'),
        'cart_total' => \Cart::getTotal(),
    ]);
});

// Additional AJAX routes
Route::get('/cart/dropdown', [CartController::class, 'loadDropdown'])->name('cart.dropdown');
Route::post('/cart/remove-ajax', [CartController::class, 'removeAjax'])->name('cart.remove.ajax');


/*
|--------------------------------------------------------------------------
| CAPTCHA
|--------------------------------------------------------------------------
*/

Route::get('captcha/{config?}', function (Captcha $captcha, $config = 'default') {
    return $captcha->create($config);
});


/*
|--------------------------------------------------------------------------
| CUSTOMER AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('customer-register', [RegisterController::class, 'registerCustomer'])->name('registerCustomer');
Route::post('customer-login', [LoginController::class, 'loginCustomer'])->name('loginCustomer');

Route::prefix('admin')->group(function () {

    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    Route::middleware(['auth', 'role:admin'])->group(function () {

        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        /*
        |--------------------------------------------------------------------------
        | VENDORS
        |--------------------------------------------------------------------------
        */

        Route::prefix('vendors')->name('admin.vendors.')->group(function () {
            Route::get('/', [VendorController::class, 'index'])->name('index');
            Route::get('/pending', [VendorController::class, 'pendingApplications'])->name('pending');
            Route::get('/active', [VendorController::class, 'activeVendors'])->name('active');
            Route::get('/suspended', [VendorController::class, 'suspendedVectors'])->name('suspended');
            Route::get('/{id}', [VendorController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [VendorController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [VendorController::class, 'reject'])->name('reject');
            Route::get('/{id}/details', [VendorController::class, 'getVendorDetails'])->name('details');
            Route::get('/stats', [VendorController::class, 'statistics'])->name('stats');
            Route::get('/{vendor}/products', [VendorController::class, 'vendorProducts'])->name('products');
        });

        /*
        |--------------------------------------------------------------------------
        | CATEGORIES
        |--------------------------------------------------------------------------
        */

        Route::prefix('categories')->name('admin.categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
            Route::post('/{category}/status', [CategoryController::class, 'updateStatus'])->name('status');
            Route::get('/{category}/subcategories', [CategoryController::class, 'getSubcategories'])->name('subcategories');
            Route::get('/dropdown-list', [CategoryController::class, 'getDropdownList'])->name('dropdown');
            Route::post('/bulk-actions', [CategoryController::class, 'bulkActions'])->name('bulk-actions');
        });

        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */

        Route::prefix('products')->name('admin.products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');

            Route::delete('/images/{image}', [ProductController::class, 'deleteImage'])->name('images.destroy');
            Route::post('/{product}/images/{image}/primary', [ProductController::class, 'setPrimaryImage'])->name('images.primary');

            Route::post('/{product}/status', [ProductController::class, 'updateStatus'])->name('status');
            Route::post('/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('toggle-featured');
            Route::post('/bulk-actions', [ProductController::class, 'bulkActions'])->name('bulk-actions');

            Route::get('/{product}/vendors/{vendor}', [ProductController::class, 'show'])->name('vendors.show');
        });

        /*
        |--------------------------------------------------------------------------
        | ORDERS
        |--------------------------------------------------------------------------
        */

        Route::prefix('orders')->name('admin.orders.')->group(function () {
            Route::get('/', [AdminOrderController::class, 'index'])->name('index');
            Route::get('/create', [AdminOrderController::class, 'create'])->name('create');
            Route::post('/', [AdminOrderController::class, 'store'])->name('store');

            // AJAX: load products under a selected vendor/shop
            Route::get('/vendor/{vendor}/products', [AdminOrderController::class, 'getVendorProducts'])
                ->name('vendor.products');

            // optional detail page
            Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        });

        Route::prefix('wallet')->name('admin.wallets.')->group(function () {
            Route::get('/', [PaymentWalletController::class, 'index'])->name('index');
            Route::get('/create', [PaymentWalletController::class, 'create'])->name('create');
            Route::post('/store', [PaymentWalletController::class, 'store'])->name('store');
            Route::get('/{wallet}/edit', [PaymentWalletController::class, 'edit'])->name('edit');
            Route::put('/{wallet}', [PaymentWalletController::class, 'update'])->name('update');
            Route::delete('/{wallet}', [PaymentWalletController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('deposits')->name('admin.wallet.')->group(function () {
            Route::get('/', [WalletDepositApprovalController::class, 'index'])->name('index');

            Route::get('/wallet-deposits/{deposit}/json', [WalletDepositApprovalController::class, 'showJson'])
                ->name('admin.wallet.deposits.show.json');

            Route::post('/wallet-deposits/{deposit}/approve-ajax', [WalletDepositApprovalController::class, 'approveAjax'])
                ->name('admin.wallet.deposits.approve.ajax');

            Route::post('/wallet-deposits/{deposit}/reject-ajax', [WalletDepositApprovalController::class, 'rejectAjax'])
                ->name('admin.wallet.deposits.reject.ajax');
        });

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        Route::prefix('users')->name('admin.users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/toggle-verification', [UserController::class, 'toggleVerification'])->name('toggle-verification');
            Route::post('/bulk-actions', [UserController::class, 'bulkActions'])->name('bulk-actions');
        });

        /*
        |--------------------------------------------------------------------------
        | SETTINGS
        |--------------------------------------------------------------------------
        */

        Route::prefix('settings')->name('admin.settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/', [SettingController::class, 'update'])->name('update');
            Route::post('/reset/{key}', [SettingController::class, 'reset'])->name('reset');
            Route::post('/clear-image/{key}', [SettingController::class, 'clearImage'])->name('clear-image');
        });

        Route::prefix('admin/wallet-options')->name('admin.wallet-options.')->group(function () {
            Route::get('/', [PayoutWalletOptionController::class, 'index'])->name('index');
            Route::get('/create', [PayoutWalletOptionController::class, 'create'])->name('create');
            Route::post('/', [PayoutWalletOptionController::class, 'store'])->name('store');
            Route::get('/{option}/edit', [PayoutWalletOptionController::class, 'edit'])->name('edit');
            Route::put('/{option}', [PayoutWalletOptionController::class, 'update'])->name('update');
            Route::delete('/{option}', [PayoutWalletOptionController::class, 'destroy'])->name('destroy');
        });
    });
});

/*
|--------------------------------------------------------------------------
| CUSTOMER (AUTHENTICATED)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/user/dashboard', [UserDashboardController::class, 'dashboard'])->name('customer.dashboard');

    Route::post('/user/password/change', [UserDashboardController::class, 'update'])->name('customer.password.change');

    // // Profile
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vendor application
    Route::get('/user/store/apply', [VendorController::class, 'apply_form'])->name('vendor.apply_form');
    Route::post('/store/apply', [VendorController::class, 'apply'])->name('vendor.apply');

    // Vendor products
    Route::get('/user/products', [VendorProductController::class, 'index'])->name('customer.products.index');
    Route::get('/user/products/category/{category}', [VendorProductController::class, 'byCategory'])->name('customer.products.byCategory');
    Route::post('/listing/add', [VendorProductController::class, 'addToListing'])->name('vendor.listing.add');

    // Vendor listings
    Route::get('/vendor/listings', [VendorProductController::class, 'myListings'])->name('vendor.listings.my');
    Route::post('/vendor/listing/{id}/remove', [VendorProductController::class, 'removeFromListing'])->name('vendor.listing.remove');
    Route::get('/vendor/balance', [UserDashboardController::class, 'vendorbalance'])->name('vendor.balance');

    // Vendor wallet
    Route::get('/user/payment-methods', [UserDashboardController::class, 'paymentMethods'])->name('customer.payment-methods');
    Route::post('/wallet/deposit/ajax', [WalletDepositController::class, 'store'])
        ->name('wallet.deposit.ajax.store');

    Route::get('/wallets/payout', [UserPayoutWalletController::class, 'index'])
        ->name('user.wallet.index');
    Route::post('/wallets/payout', [UserPayoutWalletController::class, 'store'])
        ->name('user.wallet.store');
    Route::put('/wallets/payout/{wallet}', [UserPayoutWalletController::class, 'update'])
        ->name('user.wallet.update');
    Route::delete('/wallets/payout/{wallet}', [UserPayoutWalletController::class, 'destroy'])
        ->name('user.wallet.destroy');

    // Delivery address
    Route::get('/vendor/delivery-addresses', [VendorDeliveryAddressController::class, 'index'])
        ->name('vendor.delivery.index');
    Route::post('/vendor/delivery-addresses', [VendorDeliveryAddressController::class, 'store'])
        ->name('vendor.delivery.store');

    Route::put('/vendor/delivery-addresses/{address}', [VendorDeliveryAddressController::class, 'update'])
        ->name('vendor.delivery.update');
    Route::delete('/vendor/delivery-addresses/{address}', [VendorDeliveryAddressController::class, 'destroy'])
        ->name('vendor.delivery.destroy');

    
    Route::get('/vendor/orders', [ProductOrderController::class, 'index'])->name('vendor.orders.index');
    Route::get('/orders/{order}/show', [ProductOrderController::class, 'show'])->name('vendor.orders.show');
   

    Route::get('/user/shop', [VendorShopController::class, 'index'])->name('vendor.shop.index');
    Route::get('/user/profile', [UserDashboardController::class, 'profile'])->name('customer.profile');
});


/*
|--------------------------------------------------------------------------
| LARAVEL BREEZE AUTH
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';