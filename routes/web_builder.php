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