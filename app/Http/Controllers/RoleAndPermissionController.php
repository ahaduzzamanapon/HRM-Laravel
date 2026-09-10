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

use App\Services\PermissionDiscoveryService;

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

        $permissionTree  = PermissionDiscoveryService::getPermissionTree();
        $permissionMatrix = PermissionDiscoveryService::getPermissionMatrix();
        $permission_have = [];
        $totalPermissions = Permission::count();

        // Branch dropdown: super admin sees all; others only see their own branch
        if (isSuperAdmin()) {
            $branches = Branch::pluck('branch_name', 'id');
        } else {
            $branchId = userBranchId();
            $branches = Branch::where('id', $branchId)->pluck('branch_name', 'id');
        }

        return view('role_and_permissions.create', compact('permissionTree', 'permissionMatrix', 'permission_have', 'branches', 'totalPermissions'));
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

        // Recursively include all parent IDs for selected child permissions
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

        $permissionMatrix = PermissionDiscoveryService::getPermissionMatrix();
        $assignedPermissionIds = $roleAndPermission->permissions->pluck('id')->toArray();
        $totalPermissions = Permission::count();

        return view('role_and_permissions.show', compact('roleAndPermission', 'permissionMatrix', 'assignedPermissionIds', 'totalPermissions'));
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

        $permissionTree  = PermissionDiscoveryService::getPermissionTree();
        $permissionMatrix = PermissionDiscoveryService::getPermissionMatrix();
        $permission_have = $roleAndPermission->permissions->pluck('id')->toArray();
        $totalPermissions = Permission::count();

        if (isSuperAdmin()) {
            $branches = Branch::pluck('branch_name', 'id');
        } else {
            $branchId = userBranchId();
            $branches = Branch::where('id', $branchId)->pluck('branch_name', 'id');
        }

        return view('role_and_permissions.edit', compact('roleAndPermission', 'permissionTree', 'permissionMatrix', 'permission_have', 'branches', 'totalPermissions'));
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

        \App\Services\AuthorizationEngine::clearCache();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Role and permissions updated in real-time!',
                'assigned_count' => count($selectedPermissions),
                'total_count' => Permission::count()
            ]);
        }

        Flash::success('Role updated successfully.');
        return redirect(route('roleAndPermissions.index'));
    }

    /**
     * Real-time AJAX permission sync endpoint.
     */
    public function syncPermissions(Request $request, $id)
    {
        if (!can('manage_roles_and_permissions') && !can('manage_roles')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized access denied.'], 403);
        }

        $roleAndPermission = RoleAndPermission::find($id);
        if (!$roleAndPermission) {
            return response()->json(['status' => 'error', 'message' => 'Role not found.'], 404);
        }

        enforceBranchOwnership($roleAndPermission);

        if ($request->has('name') && !empty($request->input('name'))) {
            $roleAndPermission->name = $request->input('name');
        }
        if ($request->has('key') && !empty($request->input('key'))) {
            $roleAndPermission->key = $request->input('key');
        }
        if (isSuperAdmin() && $request->has('branch_id')) {
            $roleAndPermission->branch_id = $request->input('branch_id') ?: null;
        }
        $roleAndPermission->save();

        $selectedPermissions = $request->input('permission', []);

        $parentIds = Permission::whereIn('id', $selectedPermissions)
            ->whereNotNull('parent_id')
            ->pluck('parent_id')
            ->toArray();

        $allPermissionIds = array_unique(array_merge($selectedPermissions, $parentIds));
        $roleAndPermission->permissions()->sync($allPermissionIds);

        \App\Services\AuthorizationEngine::clearCache();

        return response()->json([
            'status' => 'success',
            'message' => 'Permissions updated in real-time!',
            'assigned_count' => count($selectedPermissions),
            'total_count' => Permission::count()
        ]);
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
