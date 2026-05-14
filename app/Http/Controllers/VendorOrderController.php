<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserWallet;
use App\Models\VendorWalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\UserMessageService;

class VendorOrderController extends Controller
{
    public function index(Request $request)
    {
        $vendor = Auth::user()->vendor ?? null;

        abort_if(!$vendor, 403, 'Vendor account not found.');

        $query = Order::query()
            ->with([
                'items.product.images',
                'vendor',
            ])
            ->where('vendor_id', $vendor->id)
            ->where(function ($q) {
                $q->where('is_scheduled', 0)
                  ->orWhereNotNull('released_at');
            });

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('customer_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(12)->withQueryString();

        return view('vendor.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $vendor = Auth::user()->vendor ?? null;

        abort_if(!$vendor, 403, 'Vendor account not found.');
        abort_if($order->vendor_id !== $vendor->id, 403, 'Unauthorized access to this order.');

        $order->load([
            'vendor',
            'items.product.images',
            'items.product.category',
        ]);

        $subtotal = $order->items->sum(function ($item) {
            return (float) $item->price * (int) $item->quantity;
        });

        $shipping = (float) ($order->shipping_cost ?? 0);
        $tax = (float) ($order->tax_amount ?? 0);
        $discount = (float) ($order->discount_amount ?? 0);
        $total = $subtotal + $shipping + $tax - $discount;

        return view('vendor.orders.show', compact(
            'order',
            'subtotal',
            'shipping',
            'tax',
            'discount',
            'total'
        ));
    }

    public function goToShipment(Request $request, Order $order)
{
    try {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor profile not found.',
            ], 404);
        }

        if ((int) $order->vendor_id !== (int) $vendor->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to process this order.',
            ], 403);
        }

        if ($order->order_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending orders can be moved to shipment.',
            ], 422);
        }

        DB::beginTransaction();

        $lockedOrder = Order::where('id', $order->id)
            ->lockForUpdate()
            ->firstOrFail();

        $wholesalePrice = (float) $lockedOrder->total_amount;

        /*
        |--------------------------------------------------------------------------
        | Vendor Wallet
        |--------------------------------------------------------------------------
        | Wallet is pulled using current authenticated vendor user_id.
        |--------------------------------------------------------------------------
        */

        $vendorUserId = $vendor->user_id;

        $vendorWallet = UserWallet::where('user_id', $vendorUserId)
            ->lockForUpdate()
            ->first();

        if (!$vendorWallet) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Vendor wallet not found. Please fund your wallet before shipment.',
            ], 404);
        }

        $vendorAvailableBalance = (float) $vendorWallet->available_balance;

        /*
        |--------------------------------------------------------------------------
        | Amount Vendor Must Pay
        |--------------------------------------------------------------------------
        | Vendor is charged the wholesale price only.
        |--------------------------------------------------------------------------
        */

        $vendorRequiredAmount = $wholesalePrice;
        $vendorShortfall = max($vendorRequiredAmount - $vendorAvailableBalance, 0);

        if ($vendorShortfall > 0) {
            DB::rollBack();

            // return response()->json([
            //     'success' => false,

            //     'message' => 'Insufficient wallet balance. Your current wallet balance is $' .
            //         number_format($vendorAvailableBalance, 2) .
            //         ', but you still need to top up $' .
            //         number_format($vendorShortfall, 2) .
            //         ' to proceed.',

            //     'wholesale_price' => number_format($wholesalePrice, 2),
            //     'vendor_required' => number_format($vendorRequiredAmount, 2),
            //     'vendor_available' => number_format($vendorAvailableBalance, 2),
            //     'top_up_needed' => number_format($vendorShortfall, 2),
            // ], 422);
            return response()->json([
    'success' => false,

    'message' => 'Insufficient wallet balance. Your current wallet balance is $' .
        number_format($vendorAvailableBalance, 2) .
        ', but you still need to top up $' .
        number_format($vendorShortfall, 2) .
        ' to proceed.',

    'wholesale_price' => number_format($wholesalePrice, 2),

    'vendor_required' => number_format($vendorRequiredAmount, 2),

    'vendor_available' => number_format($vendorAvailableBalance, 2),

    'top_up_needed' => number_format($vendorShortfall, 2),

    // wallet funding route
    'fund_wallet_url' => route('vendor.balance'),

], 422);

        }

        // $vendorWallet->decrement('available_balance', $vendorRequiredAmount);
        // $vendorWallet->increment('on_hold', $vendorRequiredAmount);
if ($vendorRequiredAmount > 0) {
    $vendorWallet->decrement('available_balance', $vendorRequiredAmount);
    $vendorWallet->increment('on_hold', $vendorRequiredAmount);

    VendorWalletTransaction::create([
        'vendor_id' => $vendor->id,
        'order_id' => $lockedOrder->id,
        'order_item_id' => null,
        'type' => 'debit',
        'amount' => $vendorRequiredAmount,
        'status' => 'pending',
        'description' => 'Wallet amount held for shipment processing on order #' . $lockedOrder->order_number,
    ]);
}
        $lockedOrder->update([
            'order_status' => 'processing',
            'shipped_at' => now(),
        ]);

        if ($lockedOrder->user_id) {
            UserMessageService::send(
                userId: $lockedOrder->user_id,
                title: 'Order Shipment Processing',
                message: 'Your order #' . $lockedOrder->order_number .
                    ' has been moved to shipment processing and is now awaiting receipt confirmation.',
                type: 'order',
                meta: [
                    'action' => 'shipment_processing',
                    'order_id' => $lockedOrder->id,
                    'order_number' => $lockedOrder->order_number,
                    'vendor_id' => $lockedOrder->vendor_id,

                    'lump_sum' => (float) $lockedOrder->subtotal,
                    'profit' => (float) $lockedOrder->discount_amount,
                    'wholesale_price' => $wholesalePrice,

                    'vendor_user_id' => $vendorUserId,
                    'vendor_covered' => $vendorRequiredAmount,

                    'status' => 'processing',
                    'processed_at' => now()->toDateTimeString(),
                ]
            );
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Order moved to shipment successfully.',
            'order_status' => 'processing',
            'wholesale_price' => number_format($wholesalePrice, 2),
            'vendor_covered' => number_format($vendorRequiredAmount, 2),
            'vendor_wallet_balance' => number_format($vendorWallet->fresh()->available_balance, 2),
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();

        Log::error('Vendor shipment processing failed', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'order_id' => $order->id ?? null,
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Shipment processing failed. Please try again.',
        ], 500);
    }
}
}
