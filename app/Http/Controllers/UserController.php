<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\User;
use App\Models\AllowanceSetting;
use App\Models\UserAllowance;
use App\Models\LeaveApplication;
use App\Services\SalaryCalculator; // Import SalaryCalculator
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Flash;
use Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:add_employee')->only(['create', 'store', 'import', 'downloadSample']);
        $this->middleware('permission:edit_employee')->only(['edit', 'update', 'updateSalary']);
        $this->middleware('permission:delete_employee')->only(['destroy']);
    }

    /**
     * Show the authenticated user's own profile with short details.
     */
    public function profile()
    {
        $user = Auth::user();

        $user->load([
            'designation',
            'department',
            'branch',
            'shift',
            'role',
            'trainingDetails',
            'promotionDetails',
            'transferDetails',
        ]);

        // Recent leave applications (last 5)
        $recentLeaves = LeaveApplication::with('leaveType')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Leave taken this year (approved only)
        $leaveBalance = LeaveApplication::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereYear('start_date', now()->year)
            ->sum('requested_days');

        // Disciplinary actions against this employee
        $disciplinaryCases = \App\Models\DepartmentalCase::with('penalty')
            ->where('employee_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.profile', compact('user', 'recentLeaves', 'leaveBalance', 'disciplinaryCases'));
    }

    public function index(Request $request)
    {
        /** @var User $users */
        $usersQuery = User::select(
                'users.id',
                'users.emp_id',
                'users.name',
                'users.last_name',
                'users.branch_id',
                'roles.name as role',
                'designations.desi_name as designation',
                'shifts.shift_name as shift',
                'branchs.branch_name as branch_name'
            )
            ->leftjoin('roles', 'users.group_id', '=', 'roles.id')
            ->leftjoin('designations', 'users.designation_id', '=', 'designations.id')
            ->leftjoin('shifts', 'users.shift_id', '=', 'shifts.id')
            ->leftjoin('branchs', 'users.branch_id', '=', 'branchs.id')
            ->where('users.status', '!=', 'admin');

        // Branch-wise filtering
        if ($request->filled('branch_id')) {
            $usersQuery->where('users.branch_id', $request->branch_id);
        }

        applyBranchScope($usersQuery, 'users.branch_id');
        $users = $usersQuery->get();

        // Branches dropdown for filter & transfer modal
        $branchesQuery = \App\Models\Branch::query();
        if (!isSuperAdmin()) {
            $bId = userBranchId();
            if ($bId) {
                $branchesQuery->where('id', $bId);
            }
        }
        $branches = $branchesQuery->pluck('branch_name', 'id');

        return view('users.index')
            ->with('users', $users)
            ->with('branches', $branches)
            ->with('selectedBranchId', $request->branch_id);
    }

    /**
     * AJAX Branch Filter Endpoint (POST)
     */
    public function filterByBranch(Request $request)
    {
        $usersQuery = User::select(
                'users.id',
                'users.emp_id',
                'users.name',
                'users.last_name',
                'users.branch_id',
                'roles.name as role',
                'designations.desi_name as designation',
                'shifts.shift_name as shift',
                'branchs.branch_name as branch_name'
            )
            ->leftjoin('roles', 'users.group_id', '=', 'roles.id')
            ->leftjoin('designations', 'users.designation_id', '=', 'designations.id')
            ->leftjoin('shifts', 'users.shift_id', '=', 'shifts.id')
            ->leftjoin('branchs', 'users.branch_id', '=', 'branchs.id')
            ->where('users.status', '!=', 'admin');

        if ($request->filled('branch_id')) {
            $usersQuery->where('users.branch_id', $request->branch_id);
        }

        applyBranchScope($usersQuery, 'users.branch_id');
        $users = $usersQuery->get();

        $html = view('users.table', compact('users'))->render();

        return response()->json([
            'status' => 'success',
            'html' => $html
        ]);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        $banks = \App\Models\BankSetup::pluck('bank_name', 'id');
        $designations = \App\Models\Designation::where('desi_status', 'Active')->pluck('desi_name', 'id');
        
        $branchesQuery = \App\Models\Branch::query();
        applyBranchScope($branchesQuery, 'id');
        $branches = $branchesQuery->pluck('branch_name', 'id');

        $departments = \App\Models\Department::where('status', 'Active')->pluck('name', 'id');
        $shifts = \App\Models\Shift::pluck('shift_name', 'id');
        $rolesQuery = \App\Models\RoleAndPermission::query();
        if (!isSuperAdmin()) {
            $branchId = userBranchId();
            if ($branchId) {
                $rolesQuery->where(function($q) use ($branchId) {
                    $q->where('branch_id', $branchId)->orWhereNull('branch_id');
                });
            }
        }
        $roles = $rolesQuery->pluck('name', 'id');
        return view('users.create')
            ->with('banks', $banks)
            ->with('designations', $designations)
            ->with('branches', $branches)
            ->with('departments', $departments)
            ->with('shifts', $shifts)
            ->with('roles', $roles);
    }


    public function store(Request $request)
    {
        $input = $request->all();
        $input['is_pf_member'] = $request->has('is_pf_member') ? 1 : 0;

        if (!isSuperAdmin()) {
            $input['branch_id'] = userBranchId();
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $folder = 'images/user';
            $customName = 'user-'.time();
            $input['image'] = uploadFile($file, $folder, $customName);
        }else{
            $input['image'] = 'no-image.png';
        }

        if ($request->has('password')) {
            $input['password'] = bcrypt($request->password);
        }else{
            $input['password'] = bcrypt('12345678');
        }

        /** @var User $users */
        $users = User::create($input);

        // Save Training Details
        if ($request->has('training_details') && is_array($request->training_details)) {
            foreach ($request->training_details as $trainingDetailData) {
                $users->trainingDetails()->create($trainingDetailData);
            }
        }

        // Save Job Experiences
        if ($request->has('job_experiences') && is_array($request->job_experiences)) {
            foreach ($request->job_experiences as $jobExperienceData) {
                $users->jobExperiences()->create($jobExperienceData);
            }
        }

        // Save Educational Qualifications
        if ($request->has('educational_qualifications') && is_array($request->educational_qualifications)) {
            foreach ($request->educational_qualifications as $educationalQualificationData) {
                $users->educationalQualifications()->create($educationalQualificationData);
            }
        }

        // Save Nominee Information
        if ($request->has('nominee_information') && is_array($request->nominee_information)) {
            foreach ($request->nominee_information as $nomineeInformationData) {
                $users->nomineeInformation()->create($nomineeInformationData);
            }
        }

        // Save Promotion Details
        if ($request->has('promotion_details') && is_array($request->promotion_details)) {
            foreach ($request->promotion_details as $promotionDetailData) {
                $users->promotionDetails()->create($promotionDetailData);
            }
        }

        // Save Salary Increments
        if ($request->has('salary_increments') && is_array($request->salary_increments)) {
            foreach ($request->salary_increments as $salaryIncrementData) {
                $users->salaryIncrements()->create($salaryIncrementData);
            }
        }

        // Save Transfer Details
        if ($request->has('transfer_details') && is_array($request->transfer_details)) {
            foreach ($request->transfer_details as $transferDetailData) {
                $users->transferDetails()->create($transferDetailData);
            }
        }

        // Save Personal Documents
        if ($request->has('personal_documents') && is_array($request->personal_documents)) {
            foreach ($request->personal_documents as $personalDocumentData) {
                $users->personalDocuments()->create($personalDocumentData);
            }
        }

        if (in_array($users->status, ['left', 'resign', 'retired'])) {
            \App\Models\EmployeeDeparture::create([
                'user_id' => $users->id,
                'status' => $users->status,
                'effective_date' => now()->format('Y-m-d'),
                'reason' => 'Status set to ' . ucfirst($users->status) . ' on creation.',
                'remarks' => 'Automatically created on employee creation.',
            ]);
        }

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }


    public function show($id)
    {
        $authUser = \Illuminate\Support\Facades\Auth::user();
        if ($authUser->role && $authUser->role->name == 'Employee' && $authUser->id != $id) {
            Flash::error('You are not authorized to view this page.');
            return redirect(route('users.index'));
        }

        /** @var User $users */
        $users = User::with([
            'trainingDetails',
            'jobExperiences',
            'educationalQualifications',
            'nomineeInformation',
            'promotionDetails',
            'salaryIncrements',
            'transferDetails',
            'personalDocuments',
            'departures'
        ])->find($id);

        if (empty($users)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }

        checkBranchAccess($users->branch_id);

        return view('users.show')->with('users', $users);
    }


    public function edit($id)
    {
        $authUser = \Illuminate\Support\Facades\Auth::user();
        if ($authUser->role && $authUser->role->name == 'Employee' && $authUser->id != $id) {
            Flash::error('You are not authorized to view this page.');
            return redirect(route('users.index'));
        }

        /** @var User $users */
        $users = User::with([
            'trainingDetails',
            'jobExperiences',
            'educationalQualifications',
            'nomineeInformation',
            'promotionDetails',
            'salaryIncrements',
            'transferDetails',
            'personalDocuments',
            'userAllowances',
            'departures'
        ])->find($id);

        if (empty($users)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }

        checkBranchAccess($users->branch_id);

        $allowanceSettings = AllowanceSetting::all();
        $banks = \App\Models\BankSetup::pluck('bank_name', 'id');
        $salaryGrades = \App\Models\SalaryGrade::all();
        $designations = \App\Models\Designation::where('desi_status', 'Active')->pluck('desi_name', 'id');
        $departments = \App\Models\Department::where('status', 'Active')->pluck('name', 'id');
        $userGender = $users->gender ? strtolower(trim($users->gender)) : null;
        $leaveTypesQuery = \App\Models\LeaveType::query();
        if ($userGender) {
            $leaveTypesQuery->where(function($q) use ($userGender) {
                $q->whereNull('gender_criteria')
                  ->orWhere('gender_criteria', '')
                  ->orWhereRaw('LOWER(gender_criteria) = ?', ['all'])
                  ->orWhereRaw('LOWER(gender_criteria) = ?', [$userGender]);
            });
        }
        $leaveTypes = $leaveTypesQuery->get();
        $userLeaveAssignments = \App\Models\UserLeaveAssignment::where('user_id', $id)->pluck('allowed_days', 'leave_type_id')->toArray();
        $assignedLeaveTypeIds = \App\Models\UserLeaveAssignment::where('user_id', $id)->pluck('leave_type_id')->toArray();

        return view('users.edit')
            ->with('users', $users)
            ->with('allowanceSettings', $allowanceSettings)
            ->with('banks', $banks)
            ->with('salaryGrades', $salaryGrades)
            ->with('designations', $designations)
            ->with('departments', $departments)
            ->with('leaveTypes', $leaveTypes)
            ->with('userLeaveAssignments', $userLeaveAssignments)
            ->with('assignedLeaveTypeIds', $assignedLeaveTypeIds);
    }

    public function saveLeaveAssignments(Request $request, $id)
    {
        $user = User::find($id);
        if (empty($user)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }
            Flash::error('User not found');
            return redirect(route('users.index'));
        }

        checkBranchAccess($user->branch_id);

        $assignedTypes = $request->input('leave_types', []);
        $days = $request->input('allowed_days', []);

        \App\Models\UserLeaveAssignment::where('user_id', $id)->delete();

        foreach ($assignedTypes as $typeId => $val) {
            $allowedDays = isset($days[$typeId]) && is_numeric($days[$typeId]) && $days[$typeId] !== '' ? (int)$days[$typeId] : null;
            \App\Models\UserLeaveAssignment::create([
                'user_id' => $id,
                'leave_type_id' => $typeId,
                'allowed_days' => $allowedDays,
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Leave assignments updated successfully.']);
        }

        Flash::success('Employee leave assignments updated successfully.');
        return redirect()->back();
    }


    public function update($id, Request $request)
    {
        /** @var User $users */
        $users = User::find($id);

        if (empty($users)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }

        checkBranchAccess($users->branch_id);

        $input = $request->all();
        $input['is_pf_member'] = $request->has('is_pf_member') ? 1 : 0;

        if (!isSuperAdmin()) {
            $input['branch_id'] = userBranchId();
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $folder = 'images/user';
            $customName = 'user-'.time();
            $input['image'] = uploadFile($file, $folder, $customName);
        }else{
            unset($input['image']);
        }
        if ($request->has('password') && !empty($request->password)) {
            $input['password'] = bcrypt($request->password);
        }else{
            unset($input['password']);
        }
        $users->fill($input);
        $users->save();

        // Update or Create Training Details
        if ($request->has('training_details') && is_array($request->training_details)) {
            foreach ($request->training_details as $trainingDetailData) {
                if (isset($trainingDetailData['id']) && !empty($trainingDetailData['id'])) {
                    // Update existing record
                    $trainingDetail = $users->trainingDetails()->find($trainingDetailData['id']);
                    if ($trainingDetail) {
                        $trainingDetail->update($trainingDetailData);
                    }
                } else {
                    // Create new record
                    $users->trainingDetails()->create($trainingDetailData);
                }
            }
        }

        // Update or Create Job Experiences
        if ($request->has('job_experiences') && is_array($request->job_experiences)) {
            foreach ($request->job_experiences as $jobExperienceData) {
                if (isset($jobExperienceData['id']) && !empty($jobExperienceData['id'])) {
                    // Update existing record
                    $jobExperience = $users->jobExperiences()->find($jobExperienceData['id']);
                    if ($jobExperience) {
                        $jobExperience->update($jobExperienceData);
                    }
                } else {
                    // Create new record
                    $users->jobExperiences()->create($jobExperienceData);
                }
            }
        }

        // Update or Create Educational Qualifications
        if ($request->has('educational_qualifications') && is_array($request->educational_qualifications)) {
            foreach ($request->educational_qualifications as $educationalQualificationData) {
                if (isset($educationalQualificationData['id']) && !empty($educationalQualificationData['id'])) {
                    // Update existing record
                    $educationalQualification = $users->educationalQualifications()->find($educationalQualificationData['id']);
                    if ($educationalQualification) {
                        $educationalQualification->update($educationalQualificationData);
                    }
                } else {
                    // Create new record
                    $users->educationalQualifications()->create($educationalQualificationData);
                }
            }
        }

        // Update or Create Nominee Information
        if ($request->has('nominee_information') && is_array($request->nominee_information)) {
            foreach ($request->nominee_information as $nomineeInformationData) {
                if (isset($nomineeInformationData['id']) && !empty($nomineeInformationData['id'])) {
                    // Update existing record
                    $nomineeInformation = $users->nomineeInformation()->find($nomineeInformationData['id']);
                    if ($nomineeInformation) {
                        $nomineeInformation->update($nomineeInformationData);
                    }
                } else {
                    // Create new record
                    $users->nomineeInformation()->create($nomineeInformationData);
                }
            }
        }

        // Update or Create Promotion Details
        if ($request->has('promotion_details') && is_array($request->promotion_details)) {
            foreach ($request->promotion_details as $promotionDetailData) {
                if (isset($promotionDetailData['id']) && !empty($promotionDetailData['id'])) {
                    // Update existing record
                    $promotionDetail = $users->promotionDetails()->find($promotionDetailData['id']);
                    if ($promotionDetail) {
                        $promotionDetail->update($promotionDetailData);
                    }
                } else {
                    // Create new record
                    $users->promotionDetails()->create($promotionDetailData);
                }
            }
        }

        // Update or Create Salary Increments
        if ($request->has('salary_increments') && is_array($request->salary_increments)) {
            foreach ($request->salary_increments as $salaryIncrementData) {
                if (isset($salaryIncrementData['id']) && !empty($salaryIncrementData['id'])) {
                    // Update existing record
                    $salaryIncrement = $users->salaryIncrements()->find($salaryIncrementData['id']);
                    if ($salaryIncrement) {
                        $salaryIncrement->update($salaryIncrementData);
                    }
                } else {
                    // Create new record
                    $users->salaryIncrements()->create($salaryIncrementData);
                }
            }
        }

        // Update or Create Transfer Details
        if ($request->has('transfer_details') && is_array($request->transfer_details)) {
            foreach ($request->transfer_details as $transferDetailData) {
                if (isset($transferDetailData['id']) && !empty($transferDetailData['id'])) {
                    // Update existing record
                    $transferDetail = $users->transferDetails()->find($transferDetailData['id']);
                    if ($transferDetail) {
                        $transferDetail->update($transferDetailData);
                    }
                } else {
                    // Create new record
                    $users->transferDetails()->create($transferDetailData);
                }
            }
        }

        // Update or Create Personal Documents
        if ($request->has('personal_documents') && is_array($request->personal_documents)) {
            foreach ($request->personal_documents as $personalDocumentData) {
                if (isset($personalDocumentData['id']) && !empty($personalDocumentData['id'])) {
                    // Update existing record
                    $personalDocument = $users->personalDocuments()->find($personalDocumentData['id']);
                    if ($personalDocument) {
                        $personalDocument->update($personalDocumentData);
                    }
                } else {
                    // Create new record
                    $users->personalDocuments()->create($personalDocumentData);
                }
            }
        }

        // Save basic_salary
        $users->basic_salary = $request->input('basic_salary', 0);

        // Update or Create User Allowances
        if ($request->has('user_allowances') && is_array($request->user_allowances)) {
            foreach ($request->user_allowances as $allowanceId => $allowanceData) {
                $isEnabled = isset($allowanceData['is_enabled']) && $allowanceData['is_enabled'] == '1';
                $customValue = isset($allowanceData['custom_value']) ? $allowanceData['custom_value'] : null;

                $userAllowance = $users->userAllowances()->where('allowance_setting_id', $allowanceId)->first();

                if ($isEnabled) {
                    if ($userAllowance) {
                        // Update existing user allowance
                        $userAllowance->update([
                            'is_enabled' => true,
                            'custom_value' => $customValue,
                        ]);
                    } else {
                        // Create new user allowance
                        $users->userAllowances()->create([
                            'allowance_setting_id' => $allowanceId,
                            'is_enabled' => true,
                            'custom_value' => $customValue,
                        ]);
                    }
                } else {
                    // If not enabled, delete the user allowance if it exists
                    if ($userAllowance) {
                        $userAllowance->delete();
                    }
                }
            }
        } else {
            // If no user_allowances are submitted, delete all existing user allowances for this user
            $users->userAllowances()->delete();
        }

        // Calculate and save gross_salary
        $salaryCalculator = new \App\Services\SalaryCalculator();
        $users->gross_salary = $salaryCalculator->calculateGrossSalary($users);
        $users->net_salary = $salaryCalculator->calculateNetSalary($users, $users->gross_salary);
        $users->save(); // Save the user model after updating basic and gross salary

        if (in_array($users->status, ['left', 'resign', 'retired'])) {
            $departure = \App\Models\EmployeeDeparture::where('user_id', $users->id)->first();
            if ($departure) {
                $departure->update([
                    'status' => $users->status,
                ]);
            } else {
                \App\Models\EmployeeDeparture::create([
                    'user_id' => $users->id,
                    'status' => $users->status,
                    'effective_date' => now()->format('Y-m-d'),
                    'reason' => 'Status changed to ' . ucfirst($users->status) . ' in user profile.',
                    'remarks' => 'Automatically created on status change.',
                ]);
            }
        } else {
            \App\Models\EmployeeDeparture::where('user_id', $users->id)->delete();
        }

        Flash::success('User updated successfully.');
        return redirect(route('users.index'));
    }

    public function updateSalary(Request $request, $id)
    {
        /** @var User $user */
        $user = User::find($id);

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }

        checkBranchAccess($user->branch_id);

        $user->salary_grade_id = $request->input('salary_grade_id');
        $user->basic_salary = $request->input('basic_salary', 0);

        if ($request->has('salary_grade_id') && $request->input('salary_grade_id') != null) {
            $salaryGrade = \App\Models\SalaryGrade::find($request->input('salary_grade_id'));
            if ($salaryGrade) {
                if ($user->basic_salary < $salaryGrade->starting_salary || $user->basic_salary > $salaryGrade->end_salary) {
                    Flash::error('Basic salary must be between ' . $salaryGrade->starting_salary . ' and ' . $salaryGrade->end_salary . '.');
                    return redirect()->back()->withInput();
                }
            }
        }

        // Update or Create User Allowances
        if ($request->has('user_allowances') && is_array($request->user_allowances)) {
            foreach ($request->user_allowances as $allowanceId => $allowanceData) {
                $allowanceSetting = AllowanceSetting::find($allowanceId);
                if (!$allowanceSetting) {
                    continue;
                }

                $isEnabled = isset($allowanceData['is_enabled']) && $allowanceData['is_enabled'] == '1';
                $customValue = isset($allowanceData['custom_value']) ? $allowanceData['custom_value'] : null;

                if (($allowanceSetting->type == 'fixed' || $allowanceSetting->type == 'percentage') && $customValue !== null) {
                    if (!is_numeric($customValue)) {
                        Flash::error('Custom value for ' . $allowanceSetting->name . ' must be a number.');
                        return redirect()->back()->withInput();
                    }
                }

                $userAllowance = $user->userAllowances()->where('allowance_setting_id', $allowanceId)->first();

                if ($isEnabled) {
                    if ($userAllowance) {
                        // Update existing user allowance
                        $userAllowance->update([
                            'is_enabled' => true,
                            'custom_value' => $customValue,
                        ]);
                    } else {
                        // Create new user allowance
                        $user->userAllowances()->create([
                            'allowance_setting_id' => $allowanceId,
                            'is_enabled' => true,
                            'custom_value' => $customValue,
                        ]);
                    }
                } else {
                    // If not enabled, delete the user allowance if it exists
                    if ($userAllowance) {
                        $userAllowance->delete();
                    }
                }
            }
        } else {
            // If no user_allowances are submitted, delete all existing user allowances for this user
            $user->userAllowances()->delete();
        }

        // Calculate and save gross_salary
        $salaryCalculator = new \App\Services\SalaryCalculator();
        $user->gross_salary = $salaryCalculator->calculateGrossSalary($user);
        $user->net_salary = $salaryCalculator->calculateNetSalary($user, $user->gross_salary);
        $user->save(); // Save the user model after updating basic and gross salary

        Flash::success('Salary structure updated successfully.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        /** @var User $users */
        $users = User::find($id);
        if (empty($users)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }

        checkBranchAccess($users->branch_id);

        $users->delete();
        Flash::success('User deleted successfully.');
        return redirect(route('users.index'));
    }
    public function downloadSample()
    {
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=employee_sample.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $columns = ['emp_id', 'first_name', 'last_name', 'email', 'phone_number', 'date_of_birth', 'date_of_join', 'gender', 'designation_id', 'department_id', 'branch_id', 'shift_id', 'role_id', 'basic_salary'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Sample row: designation_id/branch_id = names, department_id can be name or empty
            fputcsv($file, ['EMP-001', 'John', 'Doe', 'john@example.com', '01712345678', '01-01-90', '01-01-23', 'Male', 'Officer', 'Finance', 'Head Office', '', '', '30000']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        if (strtolower($extension) !== 'csv') {
             Flash::error('Only CSV files are supported at the moment.');
             return redirect()->back();
        }

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ",");
            // Remove BOM if present in first element of header
            if (isset($header[0]) && strpos($header[0], "\xEF\xBB\xBF") === 0) {
                $header[0] = substr($header[0], 3);
            }
            // Trim all header names
            $header = array_map('trim', $header);

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Pad or trim $data to match header count
                $data = array_slice(array_pad($data, count($header), null), 0, count($header));
                $row  = array_combine($header, $data);

                // Resolve designation by name — create if not exists
                $designationId = null;
                $designationName = trim($row['designation_id'] ?? '');
                if ($designationName !== '') {
                    $designation = \App\Models\Designation::firstOrCreate(
                        ['desi_name' => $designationName],
                        ['desi_status' => 'active']
                    );
                    $designationId = $designation->id;
                }

                // Resolve department by name — create if not exists
                $departmentId = null;
                $departmentName = trim($row['department_id'] ?? '');
                if ($departmentName !== '') {
                    $department = \App\Models\Department::firstOrCreate(
                        ['name' => $departmentName],
                        ['status' => 'active']
                    );
                    $departmentId = $department->id;
                }

                // Resolve branch by name — create if not exists
                $branchId = null;
                $branchName = trim($row['branch_id'] ?? '');
                if ($branchName !== '') {
                    $branch = \App\Models\Branch::firstOrCreate(
                        ['branch_name' => $branchName],
                        ['status' => 'active', 'Address' => '','description' => '']
                    );
                    $branchId = $branch->id;
                }

                // Handle #N/A or non-numeric salary
                $rawSalary   = trim($row['basic_salary'] ?? '0');
                $basicSalary = is_numeric($rawSalary) ? (float) $rawSalary : 0;

                // Parse dates from dd-mm-yy or dd-mm-yyyy format
                $dateOfBirth = $this->parseCsvDate($row['date_of_birth'] ?? '');
                $dateOfJoin  = $this->parseCsvDate($row['date_of_join'] ?? '');

                try {
                    User::create([
                        'emp_id'        => trim($row['emp_id'] ?? ''),
                        'name'          => trim($row['first_name'] ?? ''),
                        'last_name'     => trim($row['last_name'] ?? ''),
                        'email'         => trim($row['email'] ?? '') ?: null,
                        'password'      => bcrypt('12345678'),
                        'phone_number'  => trim($row['phone_number'] ?? '') ?: null,
                        'date_of_birth' => $dateOfBirth,
                        'date_of_join'  => $dateOfJoin,
                        'gender'        => trim($row['gender'] ?? '') ?: null,
                        'designation_id'=> $designationId,
                        'department_id' => $departmentId,
                        'branch_id'     => $branchId,
                        'shift_id'      => null,
                        'group_id'      => null,
                        'basic_salary'  => $basicSalary,
                        'image'         => 'no-image.png',
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = 'Row emp_id=' . ($row['emp_id'] ?? '?') . ': ' . $e->getMessage();
                    $skipped++;
                }
            }
            fclose($handle);
        }

        $errorSummary = !empty($errors) ? ' Errors: ' . implode(' | ', array_slice($errors, 0, 3)) : '';
        Flash::success("Import complete: {$imported} imported, {$skipped} skipped.{$errorSummary}");
        return redirect(route('users.index'));
    }

    /**
     * Parse a date string in dd-mm-yy or dd-mm-yyyy format into Y-m-d.
     * Returns null if empty or unparseable.
     */
    private function parseCsvDate(?string $value): ?string
    {
        $value = trim($value ?? '');
        if ($value === '') {
            return null;
        }

        // Try dd-mm-yyyy first
        try {
            $date = \Carbon\Carbon::createFromFormat('d-m-Y', $value);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {}

        // Try dd-mm-yy (Carbon uses century pivot: <=68 => 2000s, >68 => 1900s)
        try {
            $date = \Carbon\Carbon::createFromFormat('d-m-y', $value);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {}

        return null;
    }
}
