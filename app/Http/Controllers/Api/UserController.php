<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
            $user->email_verified_at = null; // force re-verification
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete(); // Soft delete
        $daysToDelete = 7;
        $user->update([
            'scheduled_for_deletion_at' => Carbon::now()->addDays($daysToDelete),
        ]);

        return response()->json([
            'message' => 'User scheduled for deletion in '.$daysToDelete.' days.',
            'scheduled_for' => $user->scheduled_for_deletion_at
        ]);
    }
}
