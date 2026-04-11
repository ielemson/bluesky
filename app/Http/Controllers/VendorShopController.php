<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

class VendorShopController extends Controller
{
    public function index()
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->route('vendor.apply_form')
                ->with('error', 'Vendor shop profile not found. Please create your store first.');
        }

        return view('customer.shop.index', compact('vendor'));
    }
}