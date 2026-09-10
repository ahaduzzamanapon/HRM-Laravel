<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalSupport;
use App\Models\User;
use App\Services\AuthorizationEngine;
use Illuminate\Support\Facades\Auth;
use Flash;

class MedicalSupportController extends Controller
{
    protected function canManageApplications($user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return false;
        }

        $isEmployee = AuthorizationEngine::isEmployeeRole($user);
        return !$isEmployee && (AuthorizationEngine::isSuperAdmin($user) || AuthorizationEngine::isHRRole($user) || can('manage_medical_supports', $user));
    }

    public function index()
    {
        $query = MedicalSupport::with(['employee', 'approver']);

        if (!$this->canManageApplications()) {
            $query->where('employee_id', Auth::id());
        }

        $medicalSupports = $query->latest()->paginate(10);
        return view('medical_supports.index', compact('medicalSupports'));
    }

    public function create()
    {
        $canManage = $this->canManageApplications();
        $users = $canManage ? User::orderBy('name')->get() : collect();
        return view('medical_supports.create', compact('users', 'canManage'));
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
            $fileName = time() . '_medical_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/welfare'), $fileName);
            $input['attachment'] = 'uploads/welfare/' . $fileName;
        }

        MedicalSupport::create($input);
        Flash::success('Medical Support application submitted successfully.');
        return redirect(route('medicalSupports.index'));
    }

    public function show($id)
    {
        $medicalSupport = MedicalSupport::with(['employee', 'approver'])->find($id);
        if (empty($medicalSupport)) {
            Flash::error('Medical Support not found');
            return redirect(route('medicalSupports.index'));
        }

        if (!$this->canManageApplications()) {
            if ($medicalSupport->employee_id != Auth::id()) {
                Flash::error('Unauthorized access to this application.');
                return redirect(route('medicalSupports.index'));
            }
        }

        return view('medical_supports.show')->with('medicalSupport', $medicalSupport);
    }

    public function edit($id)
    {
        $medicalSupport = MedicalSupport::find($id);
        if (empty($medicalSupport)) {
            Flash::error('Medical Support not found');
            return redirect(route('medicalSupports.index'));
        }

        $canManage = $this->canManageApplications();
        if (!$canManage) {
            if ($medicalSupport->employee_id != Auth::id()) {
                Flash::error('Unauthorized access to this application.');
                return redirect(route('medicalSupports.index'));
            }
        }

        $users = $canManage ? User::orderBy('name')->get() : collect();
        return view('medical_supports.edit', compact('medicalSupport', 'users', 'canManage'));
    }

    public function update(Request $request, $id)
    {
        $medicalSupport = MedicalSupport::find($id);
        if (empty($medicalSupport)) {
            Flash::error('Medical Support not found');
            return redirect(route('medicalSupports.index'));
        }

        $canManage = $this->canManageApplications();
        if (!$canManage && $medicalSupport->employee_id != Auth::id()) {
            Flash::error('Unauthorized access to this application.');
            return redirect(route('medicalSupports.index'));
        }

        $input = $request->all();
        if (!$canManage) {
            unset($input['employee_id'], $input['status']);
        }

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_medical_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/welfare'), $fileName);
            $input['attachment'] = 'uploads/welfare/' . $fileName;
        }

        $medicalSupport->fill($input);
        $medicalSupport->save();
        Flash::success('Medical Support updated successfully.');
        return redirect(route('medicalSupports.index'));
    }

    public function destroy($id)
    {
        $medicalSupport = MedicalSupport::find($id);
        if (empty($medicalSupport)) {
            Flash::error('Medical Support not found');
            return redirect(route('medicalSupports.index'));
        }

        if (!$this->canManageApplications() && $medicalSupport->employee_id != Auth::id()) {
            Flash::error('Unauthorized access.');
            return redirect(route('medicalSupports.index'));
        }

        $medicalSupport->delete();
        Flash::success('Medical Support deleted successfully.');
        return redirect(route('medicalSupports.index'));
    }
}
