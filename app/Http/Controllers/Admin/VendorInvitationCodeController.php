<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorInvitationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorInvitationCodeController extends Controller
{
    public function index()
    {
        $codes = VendorInvitationCode::with('creator')
            ->latest()
            ->paginate(20);

        return view('admin.vendor-invitation-codes.index', compact('codes'));
    }

    public function create()
    {
        return view('admin.vendor-invitation-codes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['code'] = $this->generateUniqueCode();
        $data['created_by'] = auth()->id();
        $data['is_active'] = $request->has('is_active');

        VendorInvitationCode::create($data);

        return redirect()
            ->route("admin.invitation.vendor-invitation-codes.index")
            ->with('success', 'Vendor invitation code created successfully.');
    }

    // public function edit(VendorInvitationCode $vendorInvitationCode)
    // {
    //     return view('admin.vendor-invitation-codes.edit', compact('vendorInvitationCode'));
    // }

    // public function update(Request $request, VendorInvitationCode $vendorInvitationCode)
    // {
    //     $data = $request->validate([
    //         'title' => ['required', 'string', 'max:255'],
    //         'location' => ['nullable', 'string', 'max:255'],
    //         'description' => ['nullable', 'string'],
    //         'usage_limit' => ['nullable', 'integer', 'min:1'],
    //         'expires_at' => ['nullable', 'date'],
    //         'is_active' => ['nullable', 'boolean'],
    //     ]);

    //     $data['is_active'] = $request->has('is_active');

    //     $vendorInvitationCode->update($data);

    //     return redirect()
    //         ->route('admin.vendor-invitation-codes.index')
    //         ->with('success', 'Vendor invitation code updated successfully.');
    // }

    public function destroy(VendorInvitationCode $vendorInvitationCode)
    {
        $vendorInvitationCode->delete();

        return back()->with('success', 'Invitation code deleted successfully.');
    }
private function generateUniqueCode(): string
{
    do {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    } while (VendorInvitationCode::where('code', $code)->exists());

    return $code;
}
}