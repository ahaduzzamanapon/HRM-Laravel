<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Flash;

class BiometricEmployeeMappingController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['department', 'designation']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('biometric_id', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);

        return view('biometric_employee_mappings.index', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'biometric_id' => 'nullable|string|max:255'
        ]);

        $user = User::findOrFail($id);

        // Prevent duplicate biometric IDs
        if ($request->filled('biometric_id')) {
            $existing = User::where('biometric_id', $request->biometric_id)
                ->where('id', '!=', $user->id)
                ->first();
            if ($existing) {
                Flash::error("Biometric ID '{$request->biometric_id}' is already assigned to {$existing->name} {$existing->last_name}.");
                return redirect()->back();
            }
        }

        $user->update([
            'biometric_id' => $request->biometric_id
        ]);

        Flash::success("Biometric ID mapping updated successfully for {$user->name}.");

        return redirect()->back();
    }
}
