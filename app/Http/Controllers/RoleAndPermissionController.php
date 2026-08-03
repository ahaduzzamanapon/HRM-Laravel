<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleAndPermissionRequest;
use App\Http\Requests\UpdateRoleAndPermissionRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\RoleAndPermission;
use App\Models\RollHas;
use App\Models\Branch;
use App\Models\Permission;
use Illuminate\Http\Request;
use Flash;
use Response;

class RoleAndPermissionController extends AppBaseController
{
    /**
     * Display a listing of roles — branch-scoped for non-super-admins.
     */
    public function index(Request $request)
    {
        $query = RoleAndPermission::with(['branch', 'permissions']);

        // Non-super-admins can only see roles belonging strictly to their own branch
        if (!isSuperAdmin()) {
            $branchId = userBranchId();
            if ($branchId) {
                $query->where('branch_id', $branchId);
            }
        }

        $roleAndPermissions = $query->paginate(15);

        return view('role_and_permissions.index')
            ->with('roleAndPermissions', $roleAndPermissions);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        if (!can('manage_roles_and_permissions') && !can('manage_roles')) {
            abort(403, 'You do not have permission to manage roles.');
        }

        $permissions    = Permission::with('children')->whereNull('parent_id')->get();
        $permission_have = [];

        // Branch dropdown: super admin sees all; others only see their own branch
        if (isSuperAdmin()) {
            $branches = Branch::pluck('branch_name', 'id');
        } else {
            $branchId = userBranchId();
            $branches = Branch::where('id', $branchId)->pluck('branch_name', 'id');
        }

        return view('role_and_permissions.create', compact('permissions', 'permission_have', 'branches'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(CreateRoleAndPermissionRequest $request)
    {
        if (!can('manage_roles_and_permissions') && !can('manage_roles')) {
            abort(403, 'You do not have permission to manage roles.');
        }

        $input1 = $request->all();
        $selectedPermissions = $input1['permission'] ?? [];
        unset($input1['permission']);

        // Non-super-admins: force branch_id to their own branch
        if (!isSuperAdmin()) {
            $input1['branch_id'] = userBranchId();
        } elseif (empty($input1['branch_id'])) {
            $input1['branch_id'] = null;
        }

        $roleAndPermission = RoleAndPermission::create($input1);

        $parentIds = Permission::whereIn('id', $selectedPermissions)
            ->whereNotNull('parent_id')
            ->pluck('parent_id')
            ->toArray();

        $allPermissionIds = array_unique(array_merge($selectedPermissions, $parentIds));
        $roleAndPermission->permissions()->sync($allPermissionIds);

        Flash::success('Role saved successfully.');
        return redirect(route('roleAndPermissions.index'));
    }

    /**
     * Display the specified role.
     */
    public function show($id)
    {
        $roleAndPermission = RoleAndPermission::with(['branch', 'permissions.parent'])->find($id);

        if (empty($roleAndPermission)) {
            Flash::error('Role not found');
            return redirect(route('roleAndPermissions.index'));
        }

        enforceBranchOwnership($roleAndPermission);

        return view('role_and_permissions.show')->with('roleAndPermission', $roleAndPermission);
    }

    /**
     * Show the form for editing a role.
     */
    public function edit($id)
    {
        if (!can('manage_roles_and_permissions') && !can('manage_roles')) {
            abort(403, 'You do not have permission to manage roles.');
        }

        $roleAndPermission = RoleAndPermission::with('branch')->find($id);

        if (empty($roleAndPermission)) {
            Flash::error('Role not found');
            return redirect(route('roleAndPermissions.index'));
        }

        enforceBranchOwnership($roleAndPermission);

        $permissions     = Permission::with('children')->whereNull('parent_id')->get();
        $permission_have = $roleAndPermission->permissions->pluck('id')->toArray();

        if (isSuperAdmin()) {
            $branches = Branch::pluck('branch_name', 'id');
        } else {
            $branchId = userBranchId();
            $branches = Branch::where('id', $branchId)->pluck('branch_name', 'id');
        }

        return view('role_and_permissions.edit', compact('roleAndPermission', 'permission_have', 'permissions', 'branches'));
    }

    /**
     * Update a role in storage.
     */
    public function update($id, UpdateRoleAndPermissionRequest $request)
    {
        if (!can('manage_roles_and_permissions') && !can('manage_roles')) {
            abort(403, 'You do not have permission to manage roles.');
        }

        $roleAndPermission = RoleAndPermission::find($id);

        if (empty($roleAndPermission)) {
            Flash::error('Role not found');
            return redirect(route('roleAndPermissions.index'));
        }

        enforceBranchOwnership($roleAndPermission);

        $input1 = $request->all();
        $selectedPermissions = $input1['permission'] ?? [];
        unset($input1['permission']);

        // Non-super-admins: keep the role locked to their branch
        if (!isSuperAdmin()) {
            $input1['branch_id'] = userBranchId();
        } elseif (empty($input1['branch_id'])) {
            $input1['branch_id'] = null;
        }

        $roleAndPermission->fill($input1);
        $roleAndPermission->save();

        $parentIds = Permission::whereIn('id', $selectedPermissions)
            ->whereNotNull('parent_id')
            ->pluck('parent_id')
            ->toArray();

        $allPermissionIds = array_unique(array_merge($selectedPermissions, $parentIds));
        $roleAndPermission->permissions()->sync($allPermissionIds);

        Flash::success('Role updated successfully.');
        return redirect(route('roleAndPermissions.index'));
    }

    /**
     * Remove a role from storage.
     */
    public function destroy($id)
    {
        if (!can('manage_roles_and_permissions') && !can('manage_roles')) {
            abort(403, 'You do not have permission to manage roles.');
        }

        $roleAndPermission = RoleAndPermission::find($id);

        if (empty($roleAndPermission)) {
            Flash::error('Role not found');
            return redirect(route('roleAndPermissions.index'));
        }

        enforceBranchOwnership($roleAndPermission);

        $roleAndPermission->delete();
        RollHas::where('roll_id', $id)->delete();

        Flash::success('Role deleted successfully.');
        return redirect(route('roleAndPermissions.index'));
    }
}
