<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminVendorController extends Controller
{
    public function pendingApplications()
    {
        $pendingApplications = Vendor::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.vendors.pending', compact('pendingApplications'));
    }

    public function activeVendors()
    {
        $activeVendors = Vendor::with('user')
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('admin.vendors.active', compact('activeVendors'));
    }

    public function suspendedVendors()
    {
        $suspendedVendors = Vendor::with('user')
            ->where('status', 'suspended')
            ->latest()
            ->get();

        return view('admin.vendors.suspended', compact('suspendedVendors'));
    }

    public function index(Request $request)
    {
        $query = Vendor::with('user');

        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected', 'suspended'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('store_name', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('id_number', 'like', "%{$search}%")
                    ->orWhere('main_business', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(10);

        return view('admin.vendors.index', compact('applications'));
    }

    public function show($id)
    {
        $vendor = Vendor::with([
                'customer.wallets',
                'user.orders' => fn($q) => $q->latest()->take(10),
                'vendorProducts.product',
            ])
            ->withCount('vendorProducts')
            ->findOrFail($id);

        return view('admin.vendors.show', compact('vendor'));
    }

    public function approve($id)
    {
        try {
            DB::beginTransaction();

            $application = Vendor::where('id', $id)
                ->where('status', 'pending')
                ->first();

            if (!$application) {
                return back()->with('error', 'Invalid or already processed application.');
            }

            $application->update(['status' => 'approved']);

            $user = User::find($application->user_id);

            if (!$user) {
                throw new \Exception('Associated user not found.');
            }

            $user->update(['is_vendor' => true]);

            DB::commit();

            return redirect()
                ->route('admin.vendors.pending')
                ->with('success', 'Vendor application approved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error approving application: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $request->validate([
                'rejection_reason' => 'required|string|max:500',
            ]);

            $application = Vendor::findOrFail($id);

            if ($application->status !== 'pending') {
                return back()->with('error', 'This application has already been processed.');
            }

            $application->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'updated_at' => now(),
            ]);

            return redirect()
                ->route('admin.vendors.pending')
                ->with('success', 'Vendor application rejected successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error rejecting application: ' . $e->getMessage());
        }
    }

    public function statistics()
    {
        return [
            'total' => Vendor::count(),
            'pending' => Vendor::where('status', 'pending')->count(),
            'approved' => Vendor::where('status', 'approved')->count(),
            'rejected' => Vendor::where('status', 'rejected')->count(),
            'suspended' => Vendor::where('status', 'suspended')->count(),
        ];
    }

    public function getVendorDetails($id)
    {
        try {
            $application = Vendor::with('user')->findOrFail($id);

            return response()->json([
                'id' => $application->id,
                'user_id' => $application->user_id,
                'store_logo' => $application->store_logo,
                'store_name' => $application->store_name,
                'contact_person' => $application->contact_person,
                'id_number' => $application->id_number,
                'invite_code' => $application->invite_code,
                'idcard_front' => $application->idcard_front,
                'idcard_back' => $application->idcard_back,
                'main_business' => $application->main_business,
                'address' => $application->address,
                'status' => $application->status,
                'rejection_reason' => $application->rejection_reason,
                'created_at' => $application->created_at,
                'updated_at' => $application->updated_at,
                'user_email' => $application->user->email ?? 'N/A',
                'user_name' => $application->user->name ?? 'N/A',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Vendor application not found'], 404);
        }
    }

    public function vendorProducts(Vendor $vendor)
    {
        $vendorProducts = VendorProduct::with('product')
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(20);

        return view('admin.vendors.product', compact('vendor', 'vendorProducts'));
    }
}
