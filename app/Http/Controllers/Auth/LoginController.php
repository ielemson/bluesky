<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
// public function loginCustomer(Request $request)
// {
//     $request->validate([
//         'contact' => 'required|string',
//         'password' => 'required|min:6',
//     ]);

//     $contact = $request->input('contact');
//     $password = $request->input('password');

//     $user = \App\Models\User::where('contact', $contact)->first();

//     if ($user && Hash::check($password, $user->password)) {

//         Auth::login($user);

//         // Here we capture the intended URL
//         $redirectUrl = $request->input('redirect_to') ?? route('customer.dashboard');

//         return response()->json([
//             'status' => true,
//             'message' => 'Login successful!',
//             'redirect_url' => $redirectUrl
//         ]);
//     }

//     return response()->json([
//         'status' => false,
//         'message' => 'Invalid contact or password.'
//     ], 401);
// }


public function loginCustomer(Request $request)
{
    $request->validate([
        'contact' => 'required|string',
        'password' => 'required|min:6',
    ]);

    $contact = $request->input('contact');
    $password = $request->input('password');

    $user = \App\Models\User::where('contact', $contact)->first();

    // Check user exists
    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid contact or password.'
        ], 401);
    }

    // Check password
    if (!Hash::check($password, $user->password)) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid contact or password.'
        ], 401);
    }

    // 🔥 Check account status
    if ($user->status !== 'active') {
        return response()->json([
            'status' => false,
            'message' => 'Your account has been suspended. Please contact support.'
        ], 403);
    }

    // Login user
    Auth::login($user);

    // Redirect handling
    $redirectUrl = $request->input('redirect_to') ?? route('customer.dashboard');

    return response()->json([
        'status' => true,
        'message' => 'Login successful!',
        'redirect_url' => $redirectUrl
    ]);
}
}
