<?php

namespace App\Http\Controllers;
use App\Models\VendorDeliveryAddress;
use Illuminate\Http\Request;

class VendorDeliveryAddressController extends Controller
{
 public function index(Request $request)
    {
        $user = $request->user();

        $addresses = $user->deliveryAddresses()
            ->orderByDesc('is_default')
            ->latest('id')
            ->get();

        return view('customer.delivery.delivery_addres', compact('addresses'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'address'            => ['required', 'string', 'max:255'],
            'phone_country_code' => ['required', 'string', 'max:5'],
            'phone_number'       => ['required', 'string', 'max:30'],
            'contact_name'       => ['required', 'string', 'max:100'],
        ]);

        $data['user_id'] = $user->id;
        $data['is_default'] = ! $user->deliveryAddresses()->exists();

        $address = VendorDeliveryAddress::create($data);

        return response()->json([
            'status'  => 'ok',
            'message' => gtrans('Delivery address added successfully.'),
            'address' => $address,
        ]);
    }

    public function update(Request $request, VendorDeliveryAddress $address)
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'address'            => ['required', 'string', 'max:255'],
            'phone_country_code' => ['required', 'string', 'max:5'],
            'phone_number'       => ['required', 'string', 'max:30'],
            'contact_name'       => ['required', 'string', 'max:100'],
        ]);

        $address->update($data);

        return response()->json([
            'status'  => 'ok',
            'message' => gtrans('Delivery address updated successfully.'),
            'address' => $address->fresh(),
        ]);
    }

    public function destroy(Request $request, VendorDeliveryAddress $address)
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        $address->delete();

        return response()->json([
            'status'  => 'ok',
            'message' => gtrans('Delivery address deleted successfully.'),
        ]);
    }

}
