<?php

namespace App\Http\Controllers;

use App\Models\ChildAllowance;
use App\Models\User;
use App\Services\AuthorizationEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildAllowanceController extends Controller
{
    /**
     * Display a listing of child allowances.
     */
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $isEmployeeRole = AuthorizationEngine::isEmployeeRole($authUser);

        $query = ChildAllowance::with(['user.department', 'user.designation', 'user.branch'])
            ->orderBy('created_at', 'desc');

        $users = collect();
        $selectedUserId = $request->user_id ?? null;

        if ($isEmployeeRole) {
            $query->where('user_id', $authUser->id);
        } else {
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('child_name', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($uq) use ($search) {
                          $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('emp_id', 'like', "%{$search}%");
                      });
                });
            }

            $usersQuery = User::where('status', 'active');
            if (function_exists('applyUserBranchScope')) {
                applyUserBranchScope($usersQuery);
            }
            $users = $usersQuery->orderBy('name')->get();
        }

        $totalAmount = (clone $query)->sum('pay_amt');
        $totalChildren = (clone $query)->count();

        $childAllowances = $query->paginate(15)->withQueryString();

        return view('child_allowances.index', compact(
            'childAllowances',
            'totalAmount',
            'totalChildren',
            'isEmployeeRole',
            'users',
            'selectedUserId'
        ));
    }

    /**
     * Store a newly created child allowance. (Admin / HR only)
     */
    public function store(Request $request)
    {
        $authUser = Auth::user();
        $isEmployeeRole = AuthorizationEngine::isEmployeeRole($authUser);

        if ($isEmployeeRole) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Employees are not authorized to create child allowances.'], 403);
            }
            abort(403, 'Employees are not authorized to create child allowances.');
        }

        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'child_name'  => 'required|string|max:255',
            'child_dob'   => 'required|date',
            'start_age'   => 'nullable|numeric',
            'start_month' => 'required',
            'pay_year'    => 'nullable|numeric',
            'end_month'   => 'required',
            'pay_amt'     => 'required|numeric|min:0',
        ]);

        $input = $request->except('_token');
        $input['updated_by'] = $authUser->id;

        if (isset($input['start_month']) && strlen($input['start_month']) === 7) {
            $input['start_month'] = $input['start_month'] . '-01';
        }

        if (isset($input['end_month']) && strlen($input['end_month']) === 7) {
            $input['end_month'] = $input['end_month'] . '-01';
        }

        $childAllowance = ChildAllowance::create($input);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Child Allowance saved successfully.', 'data' => $childAllowance], 201);
        }

        return redirect()->route('childAllowances.index')->with('flash_notification', [
            ['message' => 'Child Allowance saved successfully.', 'level' => 'success']
        ]);
    }

    /**
     * Show the form for editing the specified child allowance. (Admin / HR only)
     */
    public function edit($id)
    {
        $authUser = Auth::user();
        $isEmployeeRole = AuthorizationEngine::isEmployeeRole($authUser);

        if ($isEmployeeRole) {
            return response()->json(['error' => 'Employees are not authorized to edit child allowances.'], 403);
        }

        $childAllowance = ChildAllowance::find($id);

        if (empty($childAllowance)) {
            return response()->json(['error' => 'Child Allowance not found'], 404);
        }

        return response()->json(['childAllowance' => $childAllowance]);
    }

    /**
     * Update the specified child allowance. (Admin / HR only)
     */
    public function update(Request $request, $id)
    {
        $authUser = Auth::user();
        $isEmployeeRole = AuthorizationEngine::isEmployeeRole($authUser);

        if ($isEmployeeRole) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Employees are not authorized to edit child allowances.'], 403);
            }
            abort(403, 'Employees are not authorized to edit child allowances.');
        }

        $childAllowance = ChildAllowance::find($id);

        if (empty($childAllowance)) {
            return response()->json(['error' => 'Child Allowance not found'], 404);
        }

        $input = $request->except('_token', '_method');
        $input['updated_by'] = $authUser->id;

        if (isset($input['start_month']) && strlen($input['start_month']) === 7) {
            $input['start_month'] = $input['start_month'] . '-01';
        }

        if (isset($input['end_month']) && strlen($input['end_month']) === 7) {
            $input['end_month'] = $input['end_month'] . '-01';
        }

        $childAllowance->update($input);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Child Allowance updated successfully.'], 200);
        }

        return redirect()->route('childAllowances.index')->with('flash_notification', [
            ['message' => 'Child Allowance updated successfully.', 'level' => 'success']
        ]);
    }

    /**
     * Remove the specified child allowance. (Admin / HR only)
     */
    public function destroy($id)
    {
        $authUser = Auth::user();
        $isEmployeeRole = AuthorizationEngine::isEmployeeRole($authUser);

        if ($isEmployeeRole) {
            return response()->json(['error' => 'Employees are not authorized to delete child allowances.'], 403);
        }

        $childAllowance = ChildAllowance::find($id);

        if (empty($childAllowance)) {
            return response()->json(['error' => 'Child Allowance not found'], 404);
        }

        $childAllowance->delete();

        return response()->json(['success' => true, 'message' => 'Child Allowance deleted successfully.'], 200);
    }

    /**
     * Get JSON list of child allowances for a specific user ID.
     */
    public function list($user_id)
    {
        $authUser = Auth::user();

        if (AuthorizationEngine::isEmployeeRole($authUser) && (int)$user_id !== (int)$authUser->id) {
            return response()->json(['error' => 'Unauthorized access to user record.'], 403);
        }

        $childAllowances = ChildAllowance::where('user_id', $user_id)->get();
        return response()->json(['childAllowances' => $childAllowances], 200);
    }
}
