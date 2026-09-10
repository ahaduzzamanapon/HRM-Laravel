<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeChildrenEducationSupport;
use App\Models\User;
use App\Services\AuthorizationEngine;
use Illuminate\Support\Facades\Auth;
use Flash;

class EmployeeChildrenEducationSupportController extends Controller
{
    protected function canManageApplications($user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return false;
        }

        $isEmployee = AuthorizationEngine::isEmployeeRole($user);
        return !$isEmployee && (AuthorizationEngine::isSuperAdmin($user) || AuthorizationEngine::isHRRole($user) || can('manage_employee_children_education_supports', $user));
    }

    public function index()
    {
        $query = EmployeeChildrenEducationSupport::with(['employee', 'approver']);

        if (!$this->canManageApplications()) {
            $query->where('employee_id', Auth::id());
        }

        $employeeChildrenEducationSupports = $query->latest()->paginate(10);
        return view('employee_children_education_supports.index', compact('employeeChildrenEducationSupports'));
    }

    public function create()
    {
        $canManage = $this->canManageApplications();
        $users = $canManage ? User::orderBy('name')->get() : collect();
        return view('employee_children_education_supports.create', compact('users', 'canManage'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $canManage = $this->canManageApplications();

        if (!$canManage) {
            $input['employee_id'] = Auth::id();
            $input['status'] = 'Pending';
        } else {
            $input['employee_id'] = $request->input('employee_id', Auth::id());
            $input['status'] = $request->input('status', 'Approved');
        }

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_education_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/welfare'), $fileName);
            $input['attachment'] = 'uploads/welfare/' . $fileName;
        }

        EmployeeChildrenEducationSupport::create($input);
        Flash::success('Employee Children Education Support application submitted successfully.');
        return redirect(route('employeeChildrenEducationSupports.index'));
    }

    public function show($id)
    {
        $employeeChildrenEducationSupport = EmployeeChildrenEducationSupport::with(['employee', 'approver'])->find($id);
        if (empty($employeeChildrenEducationSupport)) {
            Flash::error('Employee Children Education Support not found');
            return redirect(route('employeeChildrenEducationSupports.index'));
        }

        if (!$this->canManageApplications()) {
            if ($employeeChildrenEducationSupport->employee_id != Auth::id()) {
                Flash::error('Unauthorized access to this application.');
                return redirect(route('employeeChildrenEducationSupports.index'));
            }
        }

        return view('employee_children_education_supports.show')->with('employeeChildrenEducationSupport', $employeeChildrenEducationSupport);
    }

    public function edit($id)
    {
        $employeeChildrenEducationSupport = EmployeeChildrenEducationSupport::find($id);
        if (empty($employeeChildrenEducationSupport)) {
            Flash::error('Employee Children Education Support not found');
            return redirect(route('employeeChildrenEducationSupports.index'));
        }

        $canManage = $this->canManageApplications();
        if (!$canManage) {
            if ($employeeChildrenEducationSupport->employee_id != Auth::id()) {
                Flash::error('Unauthorized access to this application.');
                return redirect(route('employeeChildrenEducationSupports.index'));
            }
        }

        $users = $canManage ? User::orderBy('name')->get() : collect();
        return view('employee_children_education_supports.edit', compact('employeeChildrenEducationSupport', 'users', 'canManage'));
    }

    public function update(Request $request, $id)
    {
        $employeeChildrenEducationSupport = EmployeeChildrenEducationSupport::find($id);
        if (empty($employeeChildrenEducationSupport)) {
            Flash::error('Employee Children Education Support not found');
            return redirect(route('employeeChildrenEducationSupports.index'));
        }

        $canManage = $this->canManageApplications();
        if (!$canManage && $employeeChildrenEducationSupport->employee_id != Auth::id()) {
            Flash::error('Unauthorized access to this application.');
            return redirect(route('employeeChildrenEducationSupports.index'));
        }

        $input = $request->all();
        if (!$canManage) {
            unset($input['employee_id'], $input['status']);
        }

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_education_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/welfare'), $fileName);
            $input['attachment'] = 'uploads/welfare/' . $fileName;
        }

        $employeeChildrenEducationSupport->fill($input);
        $employeeChildrenEducationSupport->save();
        Flash::success('Employee Children Education Support updated successfully.');
        return redirect(route('employeeChildrenEducationSupports.index'));
    }

    public function destroy($id)
    {
        $employeeChildrenEducationSupport = EmployeeChildrenEducationSupport::find($id);
        if (empty($employeeChildrenEducationSupport)) {
            Flash::error('Employee Children Education Support not found');
            return redirect(route('employeeChildrenEducationSupports.index'));
        }

        if (!$this->canManageApplications() && $employeeChildrenEducationSupport->employee_id != Auth::id()) {
            Flash::error('Unauthorized access.');
            return redirect(route('employeeChildrenEducationSupports.index'));
        }

        $employeeChildrenEducationSupport->delete();
        Flash::success('Employee Children Education Support deleted successfully.');
        return redirect(route('employeeChildrenEducationSupports.index'));
    }
}
