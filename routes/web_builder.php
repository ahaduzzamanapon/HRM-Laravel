<?php




Route::post('ckeditor/upload', 'CKEditorController@upload')->name('ckeditor.upload');

Route::resource('siteSettings', 'SiteSettingController');
Route::get('users/sample', 'UserController@downloadSample')->name('users.sample');
Route::post('users/import', 'UserController@import')->name('users.import');
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

Route::resource('providentFundLoans', 'ProvidentFundLoanController');
Route::resource('providentFundLoanRepayments', 'ProvidentFundLoanRepaymentController');

Route::resource('salaryGrades', 'SalaryGradeController');
Route::resource('bankSetups', 'BankSetupController');
Route::resource('childAllowances', 'ChildAllowanceController');
Route::get('childAllowances/list/{user_id}', [\App\Http\Controllers\ChildAllowanceController::class, 'list']);
Route::resource('employeeDepartures', 'EmployeeDepartureController');
Route::get('employeeDepartures/list/{user_id}', [\App\Http\Controllers\EmployeeDepartureController::class, 'list']);

Route::resource('taxSetups', 'TaxSetupController');

// Biometric ADMS Admin Routes
Route::resource('biometricDevices', 'BiometricDeviceController');
Route::resource('biometricAttendanceLogs', 'BiometricAttendanceLogController');
Route::resource('biometricEmployeeMappings', 'BiometricEmployeeMappingController')->only(['index', 'update']);
Route::resource('biometricCommands', 'BiometricCommandController');

// Recruitment System Admin Routes
Route::get('admin/recruitment/dashboard', 'Admin\RecruitmentDashboardController@index')->name('admin.recruitment.dashboard');
Route::resource('recruitments', 'RecruitmentController'); // Original recruitment settings 
Route::resource('admin/jobs', 'Admin\JobPostController')->names('admin.jobs')->parameters(['jobs' => 'jobPost']);
Route::get('admin/applications', 'Admin\ApplicationViewerController@index')->name('admin.applications.index');
Route::patch('admin/applications/{application}/status', 'Admin\ApplicationViewerController@updateStatus')->name('admin.applications.update-status');
Route::get('admin/applications/{application}/download', 'Admin\ApplicationViewerController@downloadResume')->name('admin.applications.download');

// Career Page Setup
Route::get('admin/career-page', 'Admin\CareerPageController@index')->name('admin.career-page.index');
Route::post('admin/career-page/update', 'Admin\CareerPageController@update')->name('admin.career-page.update');