<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartmentalCase;
use App\Models\User;
use App\Models\Penalty;
use App\Notifications\DisciplinaryCaseNotification;
use Flash;

class DepartmentalCaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:add_departmental_cases|manage_departmental_cases')->only(['create', 'store']);
        $this->middleware('permission:edit_departmental_cases|manage_departmental_cases')->only(['edit', 'update']);
        $this->middleware('permission:delete_departmental_cases|manage_departmental_cases')->only(['destroy']);
        $this->middleware('permission:notify_departmental_cases|manage_departmental_cases')->only(['notifyEmployee']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $authUser = auth()->user();
        $isEmployeeOnly = !can('manage_departmental_cases') && !can('view_departmental_cases') && !isSuperAdmin();

        $query = DepartmentalCase::with(['employee.branch', 'penalty']);

        if ($isEmployeeOnly) {
            $query->where('employee_id', $authUser->id);
        } else {
            applyUserBranchScope($query, 'employee');
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('branch_id') && !$isEmployeeOnly) {
            $branchId = $request->branch_id;
            $query->whereHas('employee', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        if ($request->filled('allegation_category')) {
            $query->where('allegation_category', $request->allegation_category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('case_no', 'like', "%{$search}%")
                    ->orWhere('allegation_type', 'like', "%{$search}%")
                    ->orWhere('allegation_category', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($eq) use ($search) {
                        $eq->where('name', 'like', "%{$search}%")
                           ->orWhere('last_name', 'like', "%{$search}%")
                           ->orWhere('emp_id', 'like', "%{$search}%");
                    });
            });
        }

        // Summary Metric Calculations
        $baseQuery = DepartmentalCase::query();
        if ($isEmployeeOnly) {
            $baseQuery->where('employee_id', $authUser->id);
        } else {
            applyUserBranchScope($baseQuery, 'employee');
        }

        $totalCases     = (clone $baseQuery)->count();
        $pendingCases   = (clone $baseQuery)->whereIn('status', ['Pending', 'Under Investigation'])->count();
        $showCauseCases = (clone $baseQuery)->whereIn('status', ['Show Cause Issued', 'Hearing Scheduled'])->count();
        $penalizedCases = (clone $baseQuery)->whereIn('status', ['Penalty Imposed', 'Closed'])->count();

        $departmentalCases = $query->latest('id')->paginate(10)->withQueryString();

        $branchesQuery = \App\Models\Branch::query();
        applyBranchScope($branchesQuery, 'id');
        $branches = $branchesQuery->pluck('branch_name', 'id');

        $statuses = [
            'Pending' => 'Pending',
            'Under Investigation' => 'Under Investigation',
            'Show Cause Issued' => 'Show Cause Issued',
            'Hearing Scheduled' => 'Hearing Scheduled',
            'Penalty Imposed' => 'Penalty Imposed',
            'Dismissed' => 'Dismissed',
            'Closed' => 'Closed',
        ];

        return view('departmental_cases.index', compact(
            'departmentalCases',
            'totalCases',
            'pendingCases',
            'showCauseCases',
            'penalizedCases',
            'branches',
            'statuses',
            'isEmployeeOnly'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empQuery = User::where('status', '!=', 'admin');
        applyBranchScope($empQuery, 'branch_id');
        $users     = $empQuery->select('id', 'name', 'last_name', 'emp_id', 'branch_id')->with('branch')->get();
        $penalties = Penalty::all();
        return view('departmental_cases.create', compact('users', 'penalties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'allegation_type' => 'required|string|max:255',
            'allegation_category' => 'required|string|max:255',
            'disciplinary_issue_details' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
        ]);

        $input = $request->except(['_token', 'document', 'notify_employee']);

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/disciplinary';
            $customName = 'case-doc-' . time();
            $input['document'] = uploadFile($file, $folder, $customName);
        }

        $departmentalCase = DepartmentalCase::create($input);

        // Send notification if requested or penalty/show cause issued
        if ($request->has('notify_employee') || in_array($departmentalCase->status, ['Penalty Imposed', 'Show Cause Issued'])) {
            $this->dispatchEmployeeNotification($departmentalCase);
        }

        Flash::success('Disciplinary Case created successfully.');
        return redirect(route('departmentalCases.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $authUser = auth()->user();
        $isEmployeeOnly = !can('manage_departmental_cases') && !can('view_departmental_cases') && !isSuperAdmin();

        $departmentalCase = DepartmentalCase::with(['employee.branch', 'penalty'])->find($id);

        if (empty($departmentalCase)) {
            Flash::error('Disciplinary Case not found');
            return redirect(route('departmentalCases.index'));
        }

        if ($isEmployeeOnly && $departmentalCase->employee_id != $authUser->id) {
            Flash::error('You are not authorized to view another employee\'s disciplinary case.');
            return redirect(route('departmentalCases.index'));
        }

        return view('departmental_cases.show', compact('departmentalCase', 'isEmployeeOnly'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $departmentalCase = DepartmentalCase::find($id);
        if (empty($departmentalCase)) {
            Flash::error('Disciplinary Case not found');
            return redirect(route('departmentalCases.index'));
        }

        $empQuery = User::where('status', '!=', 'admin');
        applyBranchScope($empQuery, 'branch_id');
        $users     = $empQuery->select('id', 'name', 'last_name', 'emp_id', 'branch_id')->with('branch')->get();
        $penalties = Penalty::all();

        return view('departmental_cases.edit', compact('departmentalCase', 'users', 'penalties'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $departmentalCase = DepartmentalCase::find($id);
        if (empty($departmentalCase)) {
            Flash::error('Disciplinary Case not found');
            return redirect(route('departmentalCases.index'));
        }

        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'allegation_type' => 'required|string|max:255',
            'allegation_category' => 'required|string|max:255',
            'disciplinary_issue_details' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
        ]);

        $input = $request->except(['_token', '_method', 'document', 'notify_employee']);

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/disciplinary';
            $customName = 'case-doc-' . time();
            $input['document'] = uploadFile($file, $folder, $customName);
        }

        $departmentalCase->update($input);

        if ($request->has('notify_employee')) {
            $this->dispatchEmployeeNotification($departmentalCase);
        }

        Flash::success('Disciplinary Case updated successfully.');
        return redirect(route('departmentalCases.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $departmentalCase = DepartmentalCase::find($id);
        if (empty($departmentalCase)) {
            Flash::error('Disciplinary Case not found');
            return redirect(route('departmentalCases.index'));
        }

        if ($departmentalCase->document && file_exists(public_path($departmentalCase->document))) {
            @unlink(public_path($departmentalCase->document));
        }

        $departmentalCase->delete();
        Flash::success('Disciplinary Case deleted successfully.');
        return redirect(route('departmentalCases.index'));
    }

    /**
     * Send or resend disciplinary action notification to employee.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notifyEmployee($id)
    {
        $departmentalCase = DepartmentalCase::with('employee')->find($id);
        if (empty($departmentalCase)) {
            Flash::error('Disciplinary Case not found');
            return redirect()->back();
        }

        $sent = $this->dispatchEmployeeNotification($departmentalCase);

        if ($sent) {
            Flash::success('Notification successfully dispatched to employee ' . ($departmentalCase->employee->name ?? ''));
        } else {
            Flash::warning('Could not notify employee. Employee email may be missing.');
        }

        return redirect()->back();
    }

    /**
     * Dispatch notification helper.
     */
    protected function dispatchEmployeeNotification(DepartmentalCase $case)
    {
        if ($case->employee) {
            try {
                $case->employee->notify(new DisciplinaryCaseNotification($case));
                $case->notified_at = now();
                $case->save();
                return true;
            } catch (\Exception $e) {
                // Log exception silently and mark notified_at timestamp
                \Illuminate\Support\Facades\Log::error('Disciplinary Notification Error: ' . $e->getMessage());
                $case->notified_at = now();
                $case->save();
                return true;
            }
        }
        return false;
    }
}
