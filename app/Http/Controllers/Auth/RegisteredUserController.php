<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function registerCustomer(Request $request)
{
    $contact = $request->input('contact');
    $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);
    $isPhone = preg_match('/^\+?\d{7,15}$/', $contact);

    if (!$isEmail && !$isPhone) {
        return response()->json([
            'errors' => ['contact' => ['Please enter a valid email or phone number.']]
        ], 422);
    }

    $rules = [
        'nickname' => 'required|string|max:255',
        'name' => 'nullable|string|max:255',
        'password' => 'required|confirmed|min:6',
        'verification_code' => 'required|string',
        'contact' => 'required|string|unique:users,contact'
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $customerId = $this->generateCustomerId();

    $user = new User();
    $user->customer_id = $customerId;
    $user->nickname = $request->nickname;
    $user->is_vendor = false;
    $user->name = $request->name;
    $user->contact = $contact; // ALWAYS save to contact
    $user->password = Hash::make($request->password);
    $user->save();

    $user->assignRole('customer');

    return response()->json([
        'status' => true,
        'message' => 'Registration successful! Please login to continue.'
    ]);
}

}
