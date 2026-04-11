<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm(){
        return view("admin.login");
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email'    => 'required|email',
    //         'password' => 'required|min:6',
    //     ]);

    //     if (Auth::attempt($request->only('email', 'password'))) {
    //         $user = Auth::user();
    //         if ($user->hasRole('admin')) {
    //             return response()->json([
    //                 'status'  => true,
    //                 'message' => 'Admin login successful!',
    //                 'redirect_url' => route('admin.dashboard')
    //             ]);
    //         } else {
    //             Auth::logout();
    //             return response()->json([
    //                 'status'  => false,
    //                 'message' => 'Access denied. Admins only.'
    //             ], 403);
    //         }
    //     }

    //     return response()->json([
    //         'status'  => false,
    //         'message' => 'Invalid email or password.'
    //     ], 401);
    // }

    public function login(Request $request)
{
    $request->validate([
        "email" => "required|email",
        "password" => "required",
        // "captcha" => "required|captcha"
    ]);

    if (!Auth::attempt($request->only("email", "password"), $request->remember)) {
        return response()->json([
            "status" => "error",
            "message" => "Invalid credentials"
        ], 401);
    }

    // Check role
    if (!Auth::user()->hasRole('admin')) {
        Auth::logout();
        return response()->json([
            "status" => "error",
            "message" => "Unauthorized: Admin only"
        ], 403);
    }

    return response()->json([
        "status" => "success",
        "message" => "Login success"
    ]);
}


}
