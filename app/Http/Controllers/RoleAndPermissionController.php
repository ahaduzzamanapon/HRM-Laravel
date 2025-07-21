<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleAndPermissionRequest;
use App\Http\Requests\UpdateRoleAndPermissionRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\RoleAndPermission;
use App\Models\RollHas;
use Illuminate\Http\Request;
use Flash;
use Response;

class RoleAndPermissionController extends AppBaseController
{
    /**
     * Display a listing of the RoleAndPermission.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var RoleAndPermission $roleAndPermissions */
        $roleAndPermissions = RoleAndPermission::paginate(10);

        return view('role_and_permissions.index')
            ->with('roleAndPermissions', $roleAndPermissions);
    }

    /**
     * Show the form for creating a new RoleAndPermission.
     *
     * @return Response
     */
    public function create()
    {
        return view('role_and_permissions.create');
    }

    /**
     * Store a newly created RoleAndPermission in storage.
     *
     * @param CreateRoleAndPermissionRequest $request
     *
     * @return Response
     */
    public function store(CreateRoleAndPermissionRequest $request)
    {
        $input1 = $request->all();
        unset($input1['permission']);
        $input2 = $request->all();

        /** @var RoleAndPermission $roleAndPermission */
        $roleAndPermission = RoleAndPermission::create($input1);
        $roll_id = $roleAndPermission->id;
        foreach ($input2['permission'] as $key => $value) {
            RollHas::create(['roll_id' => $roll_id, 'permission_id' => $value]);
        }

        Flash::success('Role And Permission saved successfully.');

        return redirect(route('roleAndPermissions.index'));
    }

    /**
     * Display the specified RoleAndPermission.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var RoleAndPermission $roleAndPermission */
        $roleAndPermission = RoleAndPermission::find($id);

        if (empty($roleAndPermission)) {
            Flash::error('Role And Permission not found');

            return redirect(route('roleAndPermissions.index'));
        }

        return view('role_and_permissions.show')->with('roleAndPermission', $roleAndPermission);
    }

    /**
     * Show the form for editing the specified RoleAndPermission.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var RoleAndPermission $roleAndPermission */
        $roleAndPermission = RoleAndPermission::find($id);

        if (empty($roleAndPermission)) {
            Flash::error('Role And Permission not found');

            return redirect(route('roleAndPermissions.index'));
        }
        $permission_have = RollHas::where('roll_id', $id)->pluck('permission_id')->toArray();


        return view('role_and_permissions.edit', compact('roleAndPermission', 'permission_have'));
    }

    /**
     * Update the specified RoleAndPermission in storage.
     *
     * @param int $id
     * @param UpdateRoleAndPermissionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRoleAndPermissionRequest $request)
    {
        /** @var RoleAndPermission $roleAndPermission */
        $roleAndPermission = RoleAndPermission::find($id);

        if (empty($roleAndPermission)) {
            Flash::error('Role And Permission not found');

            return redirect(route('roleAndPermissions.index'));
        }

        $input1 = $request->all();
        unset($input1['permission']);
        $input2 = $request->all();
        $roleAndPermission->fill($input1);
        $roleAndPermission->save();

        RollHas::where('roll_id', $id)->delete();
        foreach ($input2['permission'] as $key => $value) {
            RollHas::create(['roll_id' => $id, 'permission_id' => $value]);
        }

        Flash::success('Role And Permission updated successfully.');

        return redirect(route('roleAndPermissions.index'));
    }

    /**
     * Remove the specified RoleAndPermission from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var RoleAndPermission $roleAndPermission */
        $roleAndPermission = RoleAndPermission::find($id);

        if (empty($roleAndPermission)) {
            Flash::error('Role And Permission not found');

            return redirect(route('roleAndPermissions.index'));
        }

        $roleAndPermission->delete();

        RollHas::where('roll_id', $id)->delete();

        Flash::success('Role And Permission deleted successfully.');

        return redirect(route('roleAndPermissions.index'));
    }
}
