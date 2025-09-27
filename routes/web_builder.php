<?php




Route::resource('siteSettings', 'SiteSettingController');
Route::resource('users', 'UserController');
Route::resource('permissions', 'PermissionController');
Route::resource('roleAndPermissions', 'RoleAndPermissionController');
Route::resource('designations', 'DesignationController');


Route::resource('departments', 'DepartmentController');

Route::resource('branches', 'BranchController');

Route::resource('holydays', 'HolydayController');

Route::resource('shifts', 'ShiftController');

Route::resource('attendanceFileUploads', 'AttendanceFileUploadController');

Route::resource('leaveTypes', 'LeaveTypeController');

Route::resource('leaveApplications', 'LeaveApplicationController');

Route::get('leaveApplications/{id}/approve', [App\Http\Controllers\LeaveApplicationController::class, 'approve'])->name('leaveApplications.approve');
Route::get('leaveApplications/{id}/reject', [App\Http\Controllers\LeaveApplicationController::class, 'reject'])->name('leaveApplications.reject');

Route::resource('movements', 'MovementController');

Route::resource('notices', 'NoticeController');

Route::resource('rewardings', 'RewardingController');

Route::resource('innovations', 'InnovationController');

Route::resource('employeeChildrenEducationSupports', 'EmployeeChildrenEducationSupportController');
Route::resource('funeralSupports', 'FuneralSupportController');
Route::resource('medicalSupports', 'MedicalSupportController');

Route::resource('penalties', 'PenaltyController');
Route::resource('departmentalCases', 'DepartmentalCaseController');

Route::resource('loanTypes', 'LoanTypeController');
Route::resource('loans', 'LoanController');
Route::resource('loanRepayments', 'LoanRepaymentController');

Route::resource('providentFundSettings', 'ProvidentFundSettingController');
Route::resource('providentFunds', 'ProvidentFundController');

Route::resource('salaryGrades', 'SalaryGradeController');
Route::resource('bankSetups', 'BankSetupController');
Route::resource('childAllowances', 'ChildAllowanceController');
Route::get('childAllowances/list/{user_id}', [\App\Http\Controllers\ChildAllowanceController::class, 'list']);

Route::resource('taxSetups', 'TaxSetupController');