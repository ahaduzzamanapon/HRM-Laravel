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