<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FuneralSupport;
use App\Models\User;
use App\Services\AuthorizationEngine;
use Illuminate\Support\Facades\Auth;
use Flash;

class FuneralSupportController extends Controller
{
    protected function canManageApplications($user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return false;
        }

        $isEmployee = AuthorizationEngine::isEmployeeRole($user);
        return !$isEmployee && (AuthorizationEngine::isSuperAdmin($user) || AuthorizationEngine::isHRRole($user) || can('manage_funeral_supports', $user));
    }

    public function index()
    {
        $query = FuneralSupport::with(['employee', 'approver']);

        if (!$this->canManageApplications()) {
            $query->where('employee_id', Auth::id());
        }

        $funeralSupports = $query->latest()->paginate(10);
        return view('funeral_supports.index', compact('funeralSupports'));
    }

    public function create()
    {
        $canManage = $this->canManageApplications();
        $users = $canManage ? User::orderBy('name')->get() : collect();
        return view('funeral_supports.create', compact('users', 'canManage'));
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
            $fileName = time() . '_funeral_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/welfare'), $fileName);
            $input['attachment'] = 'uploads/welfare/' . $fileName;
        }

        FuneralSupport::create($input);
        Flash::success('Funeral Support application submitted successfully.');
        return redirect(route('funeralSupports.index'));
    }

    public function show($id)
    {
        $funeralSupport = FuneralSupport::with(['employee', 'approver'])->find($id);
        if (empty($funeralSupport)) {
            Flash::error('Funeral Support not found');
            return redirect(route('funeralSupports.index'));
        }

        if (!$this->canManageApplications()) {
            if ($funeralSupport->employee_id != Auth::id()) {
                Flash::error('Unauthorized access to this application.');
                return redirect(route('funeralSupports.index'));
            }
        }

        return view('funeral_supports.show')->with('funeralSupport', $funeralSupport);
    }

    public function edit($id)
    {
        $funeralSupport = FuneralSupport::find($id);
        if (empty($funeralSupport)) {
            Flash::error('Funeral Support not found');
            return redirect(route('funeralSupports.index'));
        }

        $canManage = $this->canManageApplications();
        if (!$canManage) {
            if ($funeralSupport->employee_id != Auth::id()) {
                Flash::error('Unauthorized access to this application.');
                return redirect(route('funeralSupports.index'));
            }
        }

        $users = $canManage ? User::orderBy('name')->get() : collect();
        return view('funeral_supports.edit', compact('funeralSupport', 'users', 'canManage'));
    }

    public function update(Request $request, $id)
    {
        $funeralSupport = FuneralSupport::find($id);
        if (empty($funeralSupport)) {
            Flash::error('Funeral Support not found');
            return redirect(route('funeralSupports.index'));
        }

        $canManage = $this->canManageApplications();
        if (!$canManage && $funeralSupport->employee_id != Auth::id()) {
            Flash::error('Unauthorized access to this application.');
            return redirect(route('funeralSupports.index'));
        }

        $input = $request->all();
        if (!$canManage) {
            unset($input['employee_id'], $input['status']);
        }

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_funeral_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/welfare'), $fileName);
            $input['attachment'] = 'uploads/welfare/' . $fileName;
        }

        $funeralSupport->fill($input);
        $funeralSupport->save();
        Flash::success('Funeral Support updated successfully.');
        return redirect(route('funeralSupports.index'));
    }

    public function destroy($id)
    {
        $funeralSupport = FuneralSupport::find($id);
        if (empty($funeralSupport)) {
            Flash::error('Funeral Support not found');
            return redirect(route('funeralSupports.index'));
        }

        if (!$this->canManageApplications() && $funeralSupport->employee_id != Auth::id()) {
            Flash::error('Unauthorized access.');
            return redirect(route('funeralSupports.index'));
        }

        $funeralSupport->delete();
        Flash::success('Funeral Support deleted successfully.');
        return redirect(route('funeralSupports.index'));
    }
}
