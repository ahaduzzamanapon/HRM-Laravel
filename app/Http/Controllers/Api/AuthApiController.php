<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends BaseApiController
{
    /**
     * POST /api/v1/login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->errorResponse('Invalid credentials', 401);
        }

        /** @var User $user */
        $user = Auth::user();
        $token = $user->createToken('hrm-api-token')->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->userProfile($user),
        ], 'Login successful');
    }

    /**
     * POST /api/v1/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(null, 'Logged out successfully');
    }

    /**
     * GET /api/v1/profile
     */
    public function profile(Request $request)
    {
        return $this->successResponse($this->userProfile($request->user()));
    }

    /**
     * POST /api/v1/change-password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        /** @var User $user */
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->errorResponse('Current password is incorrect', 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return $this->successResponse(null, 'Password changed successfully');
    }

    private function userProfile(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'emp_id' => $user->emp_id,
            'designation' => optional($user->designation)->name,
            'department' => optional($user->department)->name,
            'branch' => optional($user->branch)->name,
            'role' => optional($user->role)->name,
            'phone_number' => $user->phone_number,
            'status' => $user->status,
            'image' => $user->image ? asset('storage/' . $user->image) : null,
            'created_at' => $user->created_at,
        ];
    }
}
