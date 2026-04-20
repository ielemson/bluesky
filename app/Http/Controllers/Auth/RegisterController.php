<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function registerCustomer(Request $request)
    {
        $contact = trim((string) $request->input('contact'));

        $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL) !== false;
        $isPhone = preg_match('/^\+?\d{7,15}$/', $contact);

        if (! $isEmail && ! $isPhone) {
            return response()->json([
                'errors' => [
                    'contact' => ['Please enter a valid email or phone number.'],
                ],
            ], 422);
        }

        $validator = Validator::make(
            [
                'nickname' => $request->input('nickname'),
                'name' => $request->input('name'),
                'password' => $request->input('password'),
                'password_confirmation' => $request->input('password_confirmation'),
                'verification_code' => $request->input('verification_code'),
                'contact' => $contact,
            ],
            [
                'nickname' => ['required', 'string', 'max:255'],
                'name' => ['nullable', 'string', 'max:255'],
                'password' => ['required', 'confirmed', 'min:6'],
                'verification_code' => ['required', 'string'],
                'contact' => ['required', 'string', 'max:255', 'unique:users,contact'],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = new User();
        $user->customer_id = $this->generateCustomerId();
        $user->nickname = $request->input('nickname');
        $user->name = $request->input('name');
        $user->contact = $contact;
        $user->is_vendor = false;
        $user->status = 'active';
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $user->assignRole('customer');

        return response()->json([
            'status' => true,
            'message' => 'Registration successful! Please login to continue.',
        ]);
    }

    /**
     * Generate a unique 5-digit numeric customer ID.
     */
    private function generateCustomerId(): string
    {
        do {
            $customerId = (string) random_int(10000, 99999);
        } while (User::where('customer_id', $customerId)->exists());

        return $customerId;
    }
}