<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    
    /**
     * Display pending vendor applications
     */
    public function pendingApplications()
    {
        $pendingApplications = Vendor::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.vendors.pending', compact('pendingApplications'));
    }


    public function activeVendors()
    {
        $activeVendors = Vendor::with('user')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.vendors.active', compact('activeVendors'));
    }
    public function suspendedVendors()
    {
        $suspendedVendors = Vendor::with('user')
            ->where('status', 'suspended')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.vendors.suspended', compact('suspendedVendors'));
    }


    /**
     * Display all vendor applications with filters
     */
    public function index(Request $request)
    {
        $query = Vendor::with('user');

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('store_name', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('id_number', 'like', "%{$search}%")
                    ->orWhere('main_business', 'like', "%{$search}%");
            });
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.vendors.active', compact('applications'));
    }

    /**
     * Show individual application details
     */
    public function show($id)
    {
    //    $vendor = Vendor::with([
    //     'customer.wallets',
    //     'user.orders' => function ($q) {
    //         $q->latest()->take(10);
    //     },
    //     'vendorProducts.product', // <- vendor products + underlying product
    // ])->findOrFail($id);

    // return view('admin.vendors.show', compact('vendor'));
     $vendor = Vendor::with([
            'customer.wallets',
            'user.orders' => function ($q) {
                $q->latest()->take(10);
            },
            'vendorProducts.product',
        ])
        ->withCount('vendorProducts')   // adds vendor_products_count
        ->findOrFail($id);

      return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * Approve vendor application
     */

    public function approve($id)
{
    try {
        DB::beginTransaction();

        $application = Vendor::where('id', $id)
            ->where('status', 'pending')
            ->first();

        if (!$application) {
            return redirect()->back()->with('error', 'Invalid or already processed application.');
        }

        // Approve vendor request
        $application->update([
            'status' => 'approved'
        ]);

        // Update user vendor status
        $user = User::find($application->user_id);

        if (!$user) {
            throw new \Exception('Associated user not found.');
        }

        $user->update([
            'is_vendor' => true
        ]);

        DB::commit();

        return redirect()
            ->route('admin.vendors.pending')
            ->with('success', 'Vendor application approved successfully!');

    } catch (\Exception $e) {

        DB::rollBack();

        return redirect()
            ->back()
            ->with('error', 'Error approving application: ' . $e->getMessage());
    }
}


    /**
     * Reject vendor application
     */
    public function reject(Request $request, $id)
    {
        try {
            $request->validate([
                'rejection_reason' => 'required|string|max:500'
            ]);

            $application = Vendor::findOrFail($id);

            if ($application->status !== 'pending') {
                return redirect()->back()->with('error', 'This application has already been processed.');
            }

            $application->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'updated_at' => now()
            ]);

            // TODO: Send rejection notification to user

            return redirect()->route('admin.vendors.pending')->with('success', 'Vendor application rejected successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error rejecting application: ' . $e->getMessage());
        }
    }

    /**
     * Get application statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => Vendor::count(),
            'pending' => Vendor::where('status', 'pending')->count(),
            'approved' => Vendor::where('status', 'approved')->count(),
            'rejected' => Vendor::where('status', 'rejected')->count(),
        ];

        return $stats;
    }
    // Vendor application by user

    /**
 * Get vendor application details for AJAX
 */
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
            'user_name' => $application->user->name ?? 'N/A'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Vendor application not found'
        ], 404);
    }
}

    public function apply_form()
    {
        // Check if user already has a vendor application
        $vendorApplication = auth()->user()->vendor;
        // dd($vendorApplication);
        return view("customer.vendor_application", compact('vendorApplication'));
    }



    //   public function apply(Request $request)
    // {
    //     // Validate request inputs
    //     $validated = $request->validate([
    //         'store_logo'    => 'required|image|max:2048',
    //         'store_name'    => 'required|string|max:255',
    //         'contact_person'=> 'required|string|max:255',
    //         'id_number'     => 'required|string|max:255',
    //         'invite_code'   => 'nullable|string|max:50',
    //         'idcard_front'  => 'required|image|max:2048',
    //         'idcard_back'   => 'required|image|max:2048',
    //         'main_business' => 'required|string',
    //         'address'       => 'required|string'
    //     ]);

    //     // Handle file uploads
    //     $paths = [
    //         'store_logo'   => $request->file('store_logo')->store('vendors', 'public'),
    //         'idcard_front' => $request->file('idcard_front')->store('vendors', 'public'),
    //         'idcard_back'  => $request->file('idcard_back')->store('vendors', 'public'),
    //     ];

    //     // Create vendor application
    //     VendorApplication::create([
    //         'store_logo'     => $paths['store_logo'],
    //         'user_id'        =>Auth()->user()->id,
    //         'store_name'     => $validated['store_name'],
    //         'contact_person' => $validated['contact_person'],
    //         'id_number'      => $validated['id_number'],
    //         'invite_code'    => $validated['invite_code'] ?? null,
    //         'idcard_front'   => $paths['idcard_front'],
    //         'idcard_back'    => $paths['idcard_back'],
    //         'main_business'  => $validated['main_business'],
    //         'address'        => $validated['address'],
    //     ]);

    //     return response()->json([
    //         'message' => 'Vendor application submitted successfully!'
    //     ], 201);
    // }

    public function apply(Request $request)
    {
        try {
            // Validate request inputs with better error messages
            $validated = $request->validate([
                'store_logo'    => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'store_name'    => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'id_number'     => 'required|string|max:255',
                'invite_code'   => 'nullable|string|max:50',
                'idcard_front'  => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'idcard_back'   => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'main_business' => 'required|string',
                'address'       => 'required|string'
            ], [
                'store_logo.required' => 'Store logo is required',
                'store_logo.image' => 'Store logo must be a valid image',
                'store_logo.max' => 'Store logo must be less than 2MB',
                'idcard_front.required' => 'ID card front image is required',
                'idcard_back.required' => 'ID card back image is required',
            ]);

            // Ensure files exist
            if (
                !$request->hasFile('store_logo') ||
                !$request->hasFile('idcard_front') ||
                !$request->hasFile('idcard_back')
            ) {
                return response()->json([
                    'message' => 'Please upload all required images'
                ], 422);
            }

            // Prepare upload directory
            $uploadPath = public_path('vendors');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Upload using move()
            try {
                $paths = [
                    'store_logo'   => $this->moveFile($request->file('store_logo'), $uploadPath),
                    'idcard_front' => $this->moveFile($request->file('idcard_front'), $uploadPath),
                    'idcard_back'  => $this->moveFile($request->file('idcard_back'), $uploadPath),
                ];
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Error uploading files: ' . $e->getMessage()
                ], 500);
            }

            // Check for existing active application
            $existingApplication = Vendor::where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'approved'])
                ->first();

            if ($existingApplication) {
                return response()->json([
                    'message' => 'You already have an active vendor application'
                ], 422);
            }

            // Create vendor application
            Vendor::create([
                'store_logo'     => $paths['store_logo'],
                'user_id'        => auth()->id(),
                'store_name'     => $validated['store_name'],
                'contact_person' => $validated['contact_person'],
                'id_number'      => $validated['id_number'],
                'invite_code'    => $validated['invite_code'] ?? null,
                'idcard_front'   => $paths['idcard_front'],
                'idcard_back'    => $paths['idcard_back'],
                'main_business'  => $validated['main_business'],
                'address'        => $validated['address'],
            ]);

            return response()->json([
                'message' => 'Vendor application submitted successfully!'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Handle file moving with unique filename
     */
    private function moveFile($file, $uploadPath)
    {
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($uploadPath, $filename);

        // Return relative path consistent with "store()"
        return 'vendors/' . $filename;
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

