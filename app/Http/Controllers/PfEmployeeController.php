<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class PfEmployeeController extends Controller
{
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $employeesQuery = User::where('is_pf_member', 1)->where('status', '!=', 'admin');

        if ($user->role && $user->role->name !== 'Super Admin') {
            $employeesQuery->where('branch_id', $user->branch_id);
        }

        $employees = $employeesQuery->get();
        return view('pf.employees.index', compact('employees'));
    }

    public function generateAccount(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Random 8-digit PF Account Number
        if (!$user->pf_account_number) {
            $user->pf_account_number = 'PF-' . strtoupper(Str::random(8));
            $user->save();
            return back()->with('success', 'PF Account generated successfully.');
        }

        return back()->with('error', 'PF Account already exists.');
    }
}
