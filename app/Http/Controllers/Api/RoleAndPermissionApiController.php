<?php

namespace App\Http\Controllers\Api;

use App\Models\RoleAndPermission;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleAndPermissionApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = RoleAndPermission::when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:role_and_permissions,name',
            'status' => 'nullable|boolean',
        ]);
        $item = RoleAndPermission::create($validated);
        return $this->successResponse($item, 'Role created successfully', 201);
    }

    public function show($id)
    {
        $item = RoleAndPermission::find($id);
        if (!$item)
            return $this->errorResponse('Role not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = RoleAndPermission::find($id);
        if (!$item)
            return $this->errorResponse('Role not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Role updated successfully');
    }

    public function destroy($id)
    {
        $item = RoleAndPermission::find($id);
        if (!$item)
            return $this->errorResponse('Role not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Role deleted successfully');
    }

    public function permissions()
    {
        $permissions = Permission::with('children')->whereNull('parent_id')->get();
        return $this->successResponse($permissions);
    }
}
