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
use App\Http\Controllers\Admin\AdminWalletTopupController;
use App\Http\Controllers\Admin\AdminVendorController;

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
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPayoutWalletController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorDeliveryAddressController;
use App\Http\Controllers\VendorProductController;
use App\Http\Controllers\VendorShopController;
use App\Http\Controllers\WalletDepositController;
use App\Http\Controllers\WalletWithdrawController;
use App\Http\Controllers\WithdrawalRequestController;
use App\Http\Controllers\UserMessageController;
use App\Http\Controllers\UserRechargeController;
use App\Http\Controllers\BillingRecordController;
use App\Http\Controllers\WithdrawalRecordController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\VendorOrderController;

/*
|--------------------------------------------------------------------------
| NEW LANGUAGE SWITCH (Google Translate)
|--------------------------------------------------------------------------
*/

Route::post('/language', [LanguageController::class, 'switch'])
    ->name('lang.switch');

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'index'])->name('home');

// Route::view('/shop', 'shop')->name('shop');
// Route::view('/cart', 'cart')->name('cart');

// View all products
Route::get('/shop/products', [PageController::class, 'shop'])
    ->name('page.products.shop');

// Filter by category (slug)
Route::get('/products/category/{categorySlug}', [PageController::class, 'shop'])
    ->name('page.products.category');

// Product details
Route::get('/product/{slug}', [PageController::class, 'product'])
    ->name('page.products.show');

/*
|--------------------------------------------------------------------------
| CART + CHECKOUT
|--------------------------------------------------------------------------
*/
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'viewCart'])->name('cart.view');

    Route::post('/add-ajax', [CartController::class, 'addAjax'])->name('cart.add.ajax');
    Route::post('/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/remove-ajax', [CartController::class, 'removeAjax'])->name('cart.remove.ajax');

    Route::get('/dropdown', [CartController::class, 'loadDropdown'])->name('cart.dropdown');
    Route::get('/summary', [CartController::class, 'summary'])->name('cart.summary');

    Route::get('/wishlist', [CartController::class, 'wishlist'])->name('wishlist.summary');
});

// Checkout
Route::prefix('checkout')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process');
});


// Route::prefix('cart')->group(function () {
//     Route::get('/', [CartController::class, 'index'])->name('cart.index');
//     Route::post('/update', [CartController::class, 'update'])->name('cart.update');
//     Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
//     Route::post('/add-ajax', [CartController::class, 'addAjax'])->name('cart.add.ajax');
//     Route::get('/shopping', [CartController::class, 'viewCart'])->name('cart.view');
//     Route::get('/wishlist', [CartController::class, 'wishlist'])->name('wishlist.summary');
//     Route::post('/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');

//     // Checkout
//     Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
//     Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
// });

// // AJAX Cart Summary
// Route::get('/cart/summary', function () {
//     return response()->json([
//         'cart_count' => \Cart::getContent()->sum('quantity'),
//         'cart_total' => \Cart::getTotal(),
//     ]);
// });

// Route::get('/cart/dropdown', [CartController::class, 'loadDropdown'])->name('cart.dropdown');
// Route::post('/cart/remove-ajax', [CartController::class, 'removeAjax'])->name('cart.remove.ajax');

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

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        /*
|--------------------------------------------------------------------------
| VENDORS
|--------------------------------------------------------------------------
*/

        Route::prefix('vendors')->name('admin.vendors.')->group(function () {
            Route::get('/', [AdminVendorController::class, 'index'])->name('index');
            Route::get('/pending', [AdminVendorController::class, 'pendingApplications'])->name('pending');
            Route::get('/active', [AdminVendorController::class, 'activeVendors'])->name('active');
            Route::get('/suspended', [AdminVendorController::class, 'suspendedVendors'])->name('suspended');
            Route::get('/stats', [AdminVendorController::class, 'statistics'])->name('stats');
            Route::get('/{id}', [AdminVendorController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [AdminVendorController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [AdminVendorController::class, 'reject'])->name('reject');
            Route::get('/{id}/details', [AdminVendorController::class, 'getVendorDetails'])->name('details');
            Route::get('/{vendor}/products', [AdminVendorController::class, 'vendorProducts'])->name('products');
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

            Route::get('/vendor/{vendor}/products', [AdminOrderController::class, 'getVendorProducts'])
                ->name('vendor.products');

            Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        });

        /*
|--------------------------------------------------------------------------
| PAYMENT WALLETS
|--------------------------------------------------------------------------
*/

        Route::prefix('wallet')->name('admin.wallets.')->group(function () {
            Route::get('/', [PaymentWalletController::class, 'index'])->name('index');
            Route::get('/create', [PaymentWalletController::class, 'create'])->name('create');
            Route::post('/store', [PaymentWalletController::class, 'store'])->name('store');
            Route::get('/{wallet}/edit', [PaymentWalletController::class, 'edit'])->name('edit');
            Route::put('/{wallet}', [PaymentWalletController::class, 'update'])->name('update');
            Route::delete('/{wallet}', [PaymentWalletController::class, 'destroy'])->name('destroy');
        });

        /*
|--------------------------------------------------------------------------
| WALLET DEPOSITS
|--------------------------------------------------------------------------
*/

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

        /*
|--------------------------------------------------------------------------
| PAYOUT WALLET OPTIONS
|--------------------------------------------------------------------------
*/

        Route::prefix('wallet-options')->name('admin.wallet-options.')->group(function () {
            Route::get('/', [PayoutWalletOptionController::class, 'index'])->name('index');
            Route::get('/create', [PayoutWalletOptionController::class, 'create'])->name('create');
            Route::post('/', [PayoutWalletOptionController::class, 'store'])->name('store');
            Route::get('/{option}/edit', [PayoutWalletOptionController::class, 'edit'])->name('edit');
            Route::put('/{option}', [PayoutWalletOptionController::class, 'update'])->name('update');
            Route::delete('/{option}', [PayoutWalletOptionController::class, 'destroy'])->name('destroy');

            Route::get('/wallet/topup', [AdminWalletTopupController::class, 'index'])->name('topup');
            Route::post('/wallet/topup', [AdminWalletTopupController::class, 'store'])->name('topup.store');
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

    // Deposit wallet
    Route::get('/user/payment-methods', [UserDashboardController::class, 'paymentMethods'])->name('customer.payment-methods');
    Route::post('/wallet/deposit/ajax', [WalletDepositController::class, 'store'])->name('wallet.deposit.ajax.store');

    // Withdrawal
    Route::get('/user/withdrawal-methods', [UserDashboardController::class, 'withdrawalMethods'])
        ->name('customer.withdrawal-methods');

    Route::get('/user/withdrawal-addresses/{option}', [UserDashboardController::class, 'withdrawalAddresses'])
        ->name('customer.withdrawal-addresses');

    Route::post('/wallet/withdraw/ajax', [WalletWithdrawController::class, 'store'])
        ->name('wallet.withdraw.ajax.store');

    Route::post('/user/withdrawal-request', [WithdrawalRequestController::class, 'store'])
        ->name('customer.withdrawal-request.store');

    // Customer payout wallets
    Route::get('/user/wallets', [UserPayoutWalletController::class, 'index'])
        ->name('customer.wallets.index');
    Route::post('/user/wallets', [UserPayoutWalletController::class, 'store'])
        ->name('customer.wallets.store');
    Route::delete('/user/wallets/{wallet}', [UserPayoutWalletController::class, 'destroy'])
        ->name('customer.wallets.destroy');
    Route::patch('/user/wallets/{wallet}/default', [UserPayoutWalletController::class, 'setDefault'])
        ->name('customer.wallets.default');

    // Delivery address
    Route::get('/vendor/delivery-addresses', [VendorDeliveryAddressController::class, 'index'])
        ->name('vendor.delivery.index');
    Route::post('/vendor/delivery-addresses', [VendorDeliveryAddressController::class, 'store'])
        ->name('vendor.delivery.store');
    Route::put('/vendor/delivery-addresses/{address}', [VendorDeliveryAddressController::class, 'update'])
        ->name('vendor.delivery.update');
    Route::delete('/vendor/delivery-addresses/{address}', [VendorDeliveryAddressController::class, 'destroy'])
        ->name('vendor.delivery.destroy');

    // Orders
    // Route::get('/vendor/orders', [ProductOrderController::class, 'index'])->name('vendor.orders.index');
    // Route::get('/orders/{order}/show', [ProductOrderController::class, 'show'])->name('vendor.orders.show');
Route::get('/vendor/orders', [VendorOrderController::class, 'index'])->name('vendor.orders.index');
    Route::get('/vendor/orders/{order}', [VendorOrderController::class, 'show'])->name('vendor.orders.show');

    Route::get('/user/orders', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/user/orders/{order}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');


    // Shop / profile
    Route::get('/user/shop', [VendorShopController::class, 'index'])->name('vendor.shop.index');
    Route::get('/user/profile', [UserDashboardController::class, 'profile'])->name('customer.profile');
    // /messages
    Route::get('/user/messages', [UserMessageController::class, 'index'])->name('customer.messages.index');
    Route::get('/user/messages/dropdown', [UserMessageController::class, 'dropdown'])->name('customer.messages.dropdown');
    Route::get('/user/messages/{id}', [UserMessageController::class, 'show'])->name('customer.messages.show');
    Route::post('/user/messages/{id}/read', [UserMessageController::class, 'markAsRead'])->name('customer.messages.read');
    Route::post('/user/messages/read-all', [UserMessageController::class, 'markAllAsRead'])->name('customer.messages.read_all');

    Route::get('/user/recharges', [UserRechargeController::class, 'index'])->name('customer.recharges.index');
    Route::post('/user/recharges', [UserRechargeController::class, 'store'])->name('customer.recharges.store');
    Route::get('/user/recharges/{recharge}', [UserRechargeController::class, 'show'])->name('customer.recharges.show');
    Route::delete('/user/recharges/{recharge}', [UserRechargeController::class, 'destroy'])->name('customer.recharges.destroy');

    Route::get('/user/billing-records', [BillingRecordController::class, 'index'])->name('customer.billing.index');
    Route::get('/user/billing-records/{billingRecord}', [BillingRecordController::class, 'show'])->name('customer.billing.show');

    Route::get('/user/withdrawal-records', [WithdrawalRecordController::class, 'index'])
        ->name('customer.withdrawals.index');

    Route::get('/user/withdrawal-records/{withdrawal}', [WithdrawalRecordController::class, 'show'])
        ->name('customer.withdrawals.show');
});

/*
|--------------------------------------------------------------------------
| LARAVEL BREEZE AUTH
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
