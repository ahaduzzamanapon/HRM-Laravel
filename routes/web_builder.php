<?php



Route::post('ckeditor/upload', 'CKEditorController@upload')->name('ckeditor.upload');

// ── Site Settings ─────────────────────────────────────────────────────────
Route::middleware('permission:manage_site_settings')->group(function () {
    Route::resource('siteSettings', 'SiteSettingController');
});

// ── Staff Management ──────────────────────────────────────────────────────
Route::middleware('permission:view_employees')->group(function () {
    Route::get('users/sample', 'UserController@downloadSample')->name('users.sample');
    Route::post('users/import', 'UserController@import')->name('users.import');
    Route::post('users/filter', 'UserController@filterByBranch')->name('users.filter');
    Route::post('users/{id}/leave-assignments', [\App\Http\Controllers\UserController::class, 'saveLeaveAssignments'])->name('users.saveLeaveAssignments');
    Route::resource('users', 'UserController');
    Route::resource('employeeDepartures', 'EmployeeDepartureController');
    Route::get('employeeDepartures/list/{user_id}', [\App\Http\Controllers\EmployeeDepartureController::class, 'list']);
});

// ── Child Allowances ──────────────────────────────────────────────────────
Route::resource('childAllowances', 'ChildAllowanceController');
Route::get('childAllowances/list/{user_id}', [\App\Http\Controllers\ChildAllowanceController::class, 'list']);

// ── Roles & Permissions ───────────────────────────────────────────────────
Route::middleware('permission:manage_roles_and_permissions')->group(function () {
    Route::resource('permissions', 'PermissionController');
    Route::post('roleAndPermissions/{id}/sync', [\App\Http\Controllers\RoleAndPermissionController::class, 'syncPermissions'])->name('roleAndPermissions.sync');
    Route::resource('roleAndPermissions', 'RoleAndPermissionController');
});

// ── Organization ──────────────────────────────────────────────────────────
Route::middleware('permission:manage_designations')->group(function () {
    Route::resource('designations', 'DesignationController');
});

Route::middleware('permission:manage_departments')->group(function () {
    Route::resource('departments', 'DepartmentController');
});

Route::middleware('permission:manage_branches')->group(function () {
    Route::resource('branches', 'BranchController');
});

Route::middleware('permission:rewardings')->group(function () {
    Route::resource('rewardings', 'RewardingController');
});

Route::middleware('permission:innovations')->group(function () {
    Route::resource('innovations', 'InnovationController');
});

// ── HR ────────────────────────────────────────────────────────────────────
Route::middleware('permission:manage_holidays')->group(function () {
    Route::resource('holydays', 'HolydayController');
});

Route::middleware('permission:manage_shifts')->group(function () {
    Route::resource('shifts', 'ShiftController');
});

Route::middleware('permission:upload_attendance_files')->group(function () {
    Route::resource('attendanceFileUploads', 'AttendanceFileUploadController');
});

Route::middleware('permission:manage_leave_types')->group(function () {
    Route::resource('leaveTypes', 'LeaveTypeController');
});

Route::middleware('permission:leave_applications')->group(function () {
    Route::post('leaveApplications/check-overlap', [\App\Http\Controllers\LeaveApplicationController::class, 'checkOverlap'])->name('leaveApplications.checkOverlap');
    Route::resource('leaveApplications', 'LeaveApplicationController');
    Route::get('leaveApplications/{id}/approve', [App\Http\Controllers\LeaveApplicationController::class, 'approve'])->name('leaveApplications.approveGet')->middleware('permission:approve_leave');
    Route::get('leaveApplications/{id}/reject', [App\Http\Controllers\LeaveApplicationController::class, 'reject'])->name('leaveApplications.rejectGet')->middleware('permission:approve_leave');
});

Route::middleware('permission:movements')->group(function () {
    Route::resource('movements', 'MovementController');
});

// ── Notices ───────────────────────────────────────────────────────────────
Route::middleware('permission:notices')->group(function () {
    Route::resource('notices', 'NoticeController');
});

// ── Welfare Fund ──────────────────────────────────────────────────────────
Route::get('welfare/dashboard', 'WelfareFundController@dashboard')->name('welfare.dashboard');
Route::get('welfare/my-statement', 'WelfareFundController@myStatement')->name('welfare.myStatement');
Route::get('welfare/ledger', 'WelfareFundController@ledger')->name('welfare.ledger');
Route::get('welfare/reports', 'WelfareFundController@reports')->name('welfare.reports');

Route::get('welfare/settings', 'WelfareFundSettingController@edit')->name('welfare.settings.edit');
Route::post('welfare/settings', 'WelfareFundSettingController@update')->name('welfare.settings.update');

Route::post('welfare/approve/{type}/{id}', 'WelfareFundController@approve')->name('welfare.approve');
Route::post('welfare/reject/{type}/{id}', 'WelfareFundController@reject')->name('welfare.reject');
Route::post('welfare/disburse/{type}/{id}', 'WelfareFundController@disburse')->name('welfare.disburse');
Route::get('welfare/download-attachment/{type}/{id}', 'WelfareAttachmentController@download')->name('welfare.downloadAttachment');

Route::middleware('permission:manage_employee_children_education_supports')->group(function () {
    Route::resource('employeeChildrenEducationSupports', 'EmployeeChildrenEducationSupportController');
});

Route::middleware('permission:manage_funeral_supports')->group(function () {
    Route::resource('funeralSupports', 'FuneralSupportController');
});

Route::middleware('permission:manage_medical_supports')->group(function () {
    Route::resource('medicalSupports', 'MedicalSupportController');
});

// ── Disciplinary Actions ──────────────────────────────────────────────────
Route::middleware('permission:manage_penalties')->group(function () {
    Route::resource('penalties', 'PenaltyController');
});

Route::middleware('permission:disciplinary_actions')->group(function () {
    Route::post('departmentalCases/{id}/notify', 'DepartmentalCaseController@notifyEmployee')->name('departmentalCases.notify');
    Route::resource('departmentalCases', 'DepartmentalCaseController');
});

// ── Loans & Advances ─────────────────────────────────────────────────────
Route::middleware('permission:manage_loan_types')->group(function () {
    Route::resource('loanTypes', 'LoanTypeController');
});

Route::middleware('permission:manage_loans')->group(function () {
    Route::resource('loans', 'LoanController');
});

Route::middleware('permission:manage_loan_repayments')->group(function () {
    Route::resource('loanRepayments', 'LoanRepaymentController');
});

// ── Provident Fund ────────────────────────────────────────────────────────
Route::middleware('permission:manage_provident_fund_settings')->group(function () {
    Route::resource('providentFundSettings', 'ProvidentFundSettingController');
});

Route::middleware('permission:view_provident_fund_statements')->group(function () {
    Route::resource('providentFunds', 'ProvidentFundController');
});

Route::middleware('permission:provident_fund')->group(function () {
    Route::resource('providentFundLoans', 'ProvidentFundLoanController');
    Route::resource('providentFundLoanRepayments', 'ProvidentFundLoanRepaymentController');
});

// ── Bonus Management ──────────────────────────────────────────────────────
Route::middleware('permission:manage_bonuses')->group(function () {
    Route::get('bonuses/disbursements', [App\Http\Controllers\BonusController::class, 'disbursements'])->name('bonuses.disbursements');
    Route::post('bonuses/disbursements/{id}/status', [App\Http\Controllers\BonusController::class, 'updatePaymentStatus'])->name('bonuses.disbursements.status');
    Route::get('bonuses/{id}/process', [App\Http\Controllers\BonusController::class, 'processForm'])->name('bonuses.process');
    Route::post('bonuses/{id}/process', [App\Http\Controllers\BonusController::class, 'processStore'])->name('bonuses.process.store');
    Route::resource('bonuses', App\Http\Controllers\BonusController::class);
});

// ── Settings ──────────────────────────────────────────────────────────────
Route::middleware('permission:manage_salaryGrades')->group(function () {
    Route::resource('salaryGrades', 'SalaryGradeController');
});

Route::middleware('permission:bankSetups')->group(function () {
    Route::resource('bankSetups', 'BankSetupController');
});

Route::middleware('permission:taxSetups')->group(function () {
    Route::resource('taxSetups', 'TaxSetupController');
});

Route::middleware('permission:manage_allowance_settings')->group(function () {
    Route::resource('allowanceSettings', 'AllowanceSettingController');
});

// ── Biometric ADMS Admin Routes ───────────────────────────────────────────
Route::middleware('permission:biometric')->group(function () {
    Route::resource('biometricDevices', 'BiometricDeviceController');
    Route::resource('biometricAttendanceLogs', 'BiometricAttendanceLogController');
    Route::resource('biometricEmployeeMappings', 'BiometricEmployeeMappingController')->only(['index', 'update']);
    Route::resource('biometricCommands', 'BiometricCommandController');
});

// ── Recruitment ───────────────────────────────────────────────────────────
Route::middleware('permission:manage_recruitment')->group(function () {
    Route::get('admin/recruitment/dashboard', 'Admin\RecruitmentDashboardController@index')->name('admin.recruitment.dashboard');
    Route::resource('recruitments', 'RecruitmentController');
    Route::resource('admin/jobs', 'Admin\JobPostController')->names('admin.jobs')->parameters(['jobs' => 'jobPost']);
    Route::get('admin/applications', 'Admin\ApplicationViewerController@index')->name('admin.applications.index');
    Route::patch('admin/applications/{application}/status', 'Admin\ApplicationViewerController@updateStatus')->name('admin.applications.update-status');
    Route::get('admin/applications/{application}/download', 'Admin\ApplicationViewerController@downloadResume')->name('admin.applications.download');

    // Career Page Setup
    Route::get('admin/career-page', 'Admin\CareerPageController@index')->name('admin.career-page.index');
    Route::post('admin/career-page/update', 'Admin\CareerPageController@update')->name('admin.career-page.update');
});