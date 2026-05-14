<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorInvitationCode;
use App\Models\VendorProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
     public function applyForm()
    {
        $vendorApplication = auth()->user()->vendor;

        return view('customer.vendor_application', compact('vendorApplication'));
    }

  public function apply(Request $request)
{
    try {
        $validated = $request->validate([
            'store_logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'store_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'id_number' => 'required|string|max:255',
            'invite_code' => 'required|string|size:6',
            'idcard_front' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'idcard_back' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'main_business' => 'required|string',
            'address' => 'required|string',
        ]);

        $invitationCode = VendorInvitationCode::where('code', $validated['invite_code'])
            ->where('is_active', true)
            ->first();

        if (!$invitationCode) {
            return response()->json([
                'message' => 'Invalid or already used invitation code',
            ], 422);
        }

        $existingApplication = Vendor::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingApplication) {
            return response()->json([
                'message' => 'You already have an active vendor application',
            ], 422);
        }

        $uploadPath = public_path('vendors');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $paths = [
            'store_logo' => $this->moveFile($request->file('store_logo'), $uploadPath),
            'idcard_front' => $this->moveFile($request->file('idcard_front'), $uploadPath),
            'idcard_back' => $this->moveFile($request->file('idcard_back'), $uploadPath),
        ];

        $vendor = Vendor::create([
            'store_logo' => $paths['store_logo'],
            'user_id' => auth()->id(),
            'store_name' => $validated['store_name'],
            'contact_person' => $validated['contact_person'],
            'id_number' => $validated['id_number'],
            'invite_code' => $validated['invite_code'],
            'vendor_invitation_code_id' => $invitationCode->id,
            'idcard_front' => $paths['idcard_front'],
            'idcard_back' => $paths['idcard_back'],
            'main_business' => $validated['main_business'],
            'address' => $validated['address'],
        ]);

        $invitationCode->update([
            'is_used' => true,
            'used_by' => auth()->id(),
            'used_at' => now(),
        ]);

        return response()->json([
            'message' => 'Vendor application submitted successfully!',
            'vendor' => $vendor,
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Server error: ' . $e->getMessage(),
        ], 500);
    }
}

    private function moveFile($file, $uploadPath)
    {
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($uploadPath, $filename);

        return 'vendors/' . $filename;
    }
}