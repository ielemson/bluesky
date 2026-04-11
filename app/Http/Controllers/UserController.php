<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nickname', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('customer_id', 'like', "%{$search}%");
            });
        }

        // Filter by user type
        if ($request->has('type') && in_array($request->type, ['vendor', 'customer'])) {
            $query->where('is_vendor', $request->type === 'vendor');
        }

        // Filter by verification status
        if ($request->has('verified') && in_array($request->verified, ['verified', 'unverified'])) {
            if ($request->verified === 'verified') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'contact' => 'nullable|string|max:20',
            'customer_id' => 'nullable|string|max:255|unique:users',
            'is_vendor' => 'boolean',
            'email_verified' => 'boolean',
        ]);

        // Generate customer ID if not provided
        if (empty($validated['customer_id'])) {
            $validated['customer_id'] = $this->generateCustomerId();
        }

        // Set email verification status
        if ($request->has('email_verified') && $request->email_verified) {
            $validated['email_verified_at'] = now();
        } else {
            $validated['email_verified_at'] = null;
        }

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Create user
        $user = User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['vendor', 'orders']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
   public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'nickname' => 'nullable|string|max:255',
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique('users')->ignore($user->id),
        ],
        'password' => 'nullable|string|min:8|confirmed',
        'contact' => 'nullable|string|max:20',
        'customer_id' => [
            'nullable',
            'string',
            'max:255',
            Rule::unique('users')->ignore($user->id),
        ],
        'is_vendor' => 'boolean',
        'email_verified' => 'boolean',
    ]);

    // Handle customer ID logic
    if (empty($validated['customer_id'])) {
        // If customer_id is empty in the request
        if (empty($user->customer_id)) {
            // User doesn't have a customer ID, generate one
            $validated['customer_id'] = $this->generateCustomerId();
        } else {
            // User already has a customer ID, keep the existing one
            $validated['customer_id'] = $user->customer_id;
        }
    }
    // If customer_id is provided in the request, use it as is

    // Update password if provided
    if ($request->filled('password')) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }

    // Set email verification status
    if ($request->has('email_verified') && $request->email_verified) {
        $validated['email_verified_at'] = now();
    } else {
        $validated['email_verified_at'] = null;
    }

    $user->update($validated);

    return redirect()->route('admin.users.index')
        ->with('success', 'User updated successfully.');
}
    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Check if user has related records
        if ($user->vendor()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete user with associated vendor profile. Please delete the vendor profile first.');
        }

        if ($user->orders()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete user with associated orders.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Generate unique customer ID
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

    /**
     * Toggle user verification status
     */
    public function toggleVerification(User $user)
    {
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $message = 'User email unverified successfully.';
        } else {
            $user->update(['email_verified_at' => now()]);
            $message = 'User email verified successfully.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_verified' => !$user->email_verified_at,
        ]);
    }

    /**
     * Bulk actions for users
     */
    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:verify,unverify,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->ids)->get();

        switch ($request->action) {
            case 'verify':
                $users->each->update(['email_verified_at' => now()]);
                $message = 'Selected users verified successfully.';
                break;

            case 'unverify':
                $users->each->update(['email_verified_at' => null]);
                $message = 'Selected users unverified successfully.';
                break;

            case 'delete':
                foreach ($users as $user) {
                    // Check if user can be deleted
                    if ($user->vendor()->exists() || $user->orders()->exists()) {
                        return response()->json([
                            'success' => false,
                            'message' => "Cannot delete user '{$user->name}' because they have associated records."
                        ], 422);
                    }
                    $user->delete();
                }
                $message = 'Selected users deleted successfully.';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
