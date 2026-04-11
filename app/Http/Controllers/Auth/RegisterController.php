<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
// use Mews\Captcha\Facades\Captcha;
class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

// public function register(Request $request)
// {
//     $contact = $request->input('contact');
//     $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);
//     $isPhone = preg_match('/^\+?\d{7,15}$/', $contact);

//     if (!$isEmail && !$isPhone) {
//         return response()->json(['errors' => ['contact' => ['Please enter a valid email or phone number.']]], 422);
//     }

//     $rules = [
//         'nickname' => 'required|string|max:255',
//         'name' => 'nullable|string|max:255',
//         'password' => 'required|confirmed|min:6',
//         'verification_code' => 'required|string',
//         'contact' => $isEmail ? 'required|email|unique:users,email' : 'required|string|unique:users,contact',
//     ];

//     $validator = Validator::make($request->all(), $rules);
//     if ($validator->fails()) {
//         return response()->json(['errors' => $validator->errors()], 422);
//     }

//     $customerId = $this->generateCustomerId();

//     $user = new User();
//     $user->customer_id = $customerId;
//     $user->nickname = $request->nickname;
//     $user->is_vendor = false;
//     $user->name = $request->name;
//     $user->password = Hash::make($request->password);

//     if ($isEmail) {
//         $user->email = $contact;
//     } else {
//         $user->contact = $contact;
//     }

//     $user->save();
//     $user->assignRole('customer');

//     Auth::login($user); // Log in immediately

//     $redirect = $request->input('redirect_to', route('customer.dashboard'));

//     return response()->json([
//         'status' => true,
//         'message' => 'Registration successful!',
//         'redirect_url' => $redirect
//     ]);
// }

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


/**
 * Generate a unique 5-digit + 1-letter customer ID.
 */
private function generateCustomerId()
{
    do {
        $digits = rand(10000, 99999);
        $letter = chr(rand(65, 90)); // A–Z
        $customerId = $digits . $letter;
    } while (User::where('customer_id', $customerId)->exists());

    return $customerId;
}


}
