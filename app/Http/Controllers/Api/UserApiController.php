<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $usersQuery = User::with(['designation', 'department', 'branch', 'role', 'shift'])
            ->when($request->search, fn($q) => $q->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('emp_id', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            }))
            ->when($request->department_id, fn($q) => $q->where('department_id', $request->department_id))
            ->when($request->branch_id, fn($q) => $q->where('branch_id', $request->branch_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status));

        applyBranchScope($usersQuery, 'branch_id');
        $users = $usersQuery->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'emp_id' => 'nullable|string|unique:users,emp_id',
            'designation_id' => 'nullable|exists:designations,id',
            'department_id' => 'nullable|exists:departments,id',
            'branch_id' => 'nullable|exists:branchs,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'salary_grade_id' => 'nullable|exists:salary_grades,id',
            'basic_salary' => 'nullable|numeric|min:0',
            'gross_salary' => 'nullable|numeric|min:0',
            'phone_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'date_of_join' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'blood_group' => 'nullable|string|max:5',
            'religion' => 'nullable|string|max:50',
            'marital_status' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emp_type' => 'nullable|string',
            'bank_id' => 'nullable|exists:banksetups,id',
            'account_no' => 'nullable|string|max:50',
            'is_pf_member' => 'nullable|boolean',
            'pay_type' => 'nullable|string',
            'group_id' => 'nullable|exists:roles,id',
            'status' => 'nullable|string',
            'biometric_id' => 'nullable|string|max:50',
            'punch_id' => 'nullable|string|max:50',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('employees', 'public');
        }

        $user = User::create($validated);
        return $this->successResponse($user->load(['designation', 'department', 'branch']), 'Employee created successfully', 201);
    }

    public function show($id)
    {
        $user = User::with([
            'designation',
            'department',
            'branch',
            'role',
            'shift',
            'trainingDetails',
            'jobExperiences',
            'educationalQualifications',
            'nomineeInformation',
            'promotionDetails',
            'salaryIncrements',
            'transferDetails',
            'personalDocuments',
            'userAllowances',
        ])->find($id);

        if (!$user)
            return $this->errorResponse('Employee not found', 404);

        if (!canManageBranch($user->branch_id)) {
            return $this->errorResponse('Unauthorized access to employee from another branch', 403);
        }

        return $this->successResponse($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user)
            return $this->errorResponse('Employee not found', 404);

        if (!canManageBranch($user->branch_id)) {
            return $this->errorResponse('Unauthorized access to employee from another branch', 403);
        }

        $validated = $request->except(['password', 'email', '_method']);

        if (!isSuperAdmin()) {
            unset($validated['branch_id']); // Lock branch_id for non-superadmin
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if ($user->image)
                Storage::disk('public')->delete($user->image);
            $validated['image'] = $request->file('image')->store('employees', 'public');
        }

        $user->update($validated);
        return $this->successResponse($user->load(['designation', 'department', 'branch']), 'Employee updated successfully');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user)
            return $this->errorResponse('Employee not found', 404);

        if (!canManageBranch($user->branch_id)) {
            return $this->errorResponse('Unauthorized access to employee from another branch', 403);
        }

        $user->delete();
        return $this->successResponse(null, 'Employee deleted successfully');
    }

    public function updateSalary(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user)
            return $this->errorResponse('Employee not found', 404);

        if (!canManageBranch($user->branch_id)) {
            return $this->errorResponse('Unauthorized access to employee from another branch', 403);
        }

        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'gross_salary' => 'required|numeric|min:0',
            'salary_grade_id' => 'nullable|exists:salary_grades,id',
            'pay_type' => 'nullable|string',
        ]);

        $user->update($validated);
        return $this->successResponse($user, 'Salary updated successfully');
    }
}
