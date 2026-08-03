<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\ZktecoAttendanceController;

Log::info('Request for ' . request()->fullUrl());

Route::post('zkteco/attendance', [ZktecoAttendanceController::class, 'store']);
Route::get('zkteco/attendance', [ZktecoAttendanceController::class, 'store']);
// ZKTeco ADMS Endpoints (Hardware device communication)
Route::get('/iclock/cdata', [\App\Http\Controllers\Api\ZKTecoADMSController::class, 'handshake']);
Route::post('/iclock/cdata', [\App\Http\Controllers\Api\ZKTecoADMSController::class, 'receiveRecords']);
Route::get('/iclock/getrequest', [\App\Http\Controllers\Api\ZKTecoADMSController::class, 'getrequest']);
Route::post('/iclock/devicecmd', [\App\Http\Controllers\Api\ZKTecoADMSController::class, 'devicecmd']);

Route::middleware('auth')->group(function () {
    include 'web_builder.php';
});
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
include 'bulder_route.php';

Auth::routes(['reset' => false]);

Route::middleware(['auth', 'permission:payroll_process'])->group(function () {
    Route::get('/payroll', [App\Http\Controllers\PayrollController::class, 'index'])->name('payroll.index');
    Route::post('payroll/process', [App\Http\Controllers\PayrollController::class, 'process'])->name('payroll.process');
    Route::post('payroll/salary-report', [App\Http\Controllers\PayrollController::class, 'salaryReport'])->name('payroll.salarySheet');
    Route::post('payroll/payslip', [App\Http\Controllers\PayrollController::class, 'payslip'])->name('payroll.payslip');
    Route::post('payroll/tax', [App\Http\Controllers\PayrollController::class, 'tax'])->name('payroll.tax');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');


Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('password/otp', [ResetPasswordController::class, 'showOtpForm'])->name('password.otp');
Route::post('password/otp', [ResetPasswordController::class, 'verifyOtp'])->name('password.verify.otp');
Route::post('password/resend-otp', [ResetPasswordController::class, 'resendOtp'])->name('password.resend.otp');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth', 'permission:view_employees'])->group(function () {
    Route::patch('users/update-salary/{id}', [App\Http\Controllers\UserController::class, 'updateSalary'])->name('users.updateSalary');
});

Route::middleware(['auth', 'permission:process_attendance'])->group(function () {
    Route::get('attendance/process', [App\Http\Controllers\AttendanceProcessController::class, 'index'])->name('attendance.process.index');
    Route::post('attendance/process', [App\Http\Controllers\AttendanceProcessController::class, 'process'])->name('attendance.process.store');
    Route::get('attendance/filter', [App\Http\Controllers\AttendanceProcessController::class, 'filterUsers'])->name('attendance.filter');
    Route::post('attendance/report', [App\Http\Controllers\AttendanceProcessController::class, 'getReportData'])->name('attendance.report');
    Route::post('attendance/manual', [App\Http\Controllers\AttendanceProcessController::class, 'storeManualAttendance'])->name('attendance.manual.store');
    // dashboard report data
    Route::post('attendance/daily-report', [App\Http\Controllers\AttendanceProcessController::class, 'getDailyReportData'])->name('attendance.daily-report');
});

Route::get('my-attendance', [App\Http\Controllers\AttendanceProcessController::class, 'myAttendance'])->name('attendance.my');




Route::middleware(['auth', 'permission:approve_leave'])->group(function () {
    Route::post('leave-applications/{id}/first-approve', [App\Http\Controllers\LeaveApplicationController::class, 'firstLevelApprove'])->name('leaveApplications.first.approve');
    Route::post('leave-applications/{id}/final-approve', [App\Http\Controllers\LeaveApplicationController::class, 'finalApprove'])->name('leaveApplications.final.approve');
    Route::post('leave-applications/{id}/reject', [App\Http\Controllers\LeaveApplicationController::class, 'reject'])->name('leaveApplications.reject');
});


// Tax Management Routes
Route::middleware(['auth', 'permission:tax_management'])->group(function () {
    Route::get('tax-management', [\App\Http\Controllers\TaxManagementController::class, 'index'])->name('taxManagement.index');
    Route::post('tax-management/fiscal-year', [\App\Http\Controllers\TaxManagementController::class, 'storeFiscalYear'])->name('taxManagement.fiscalYear.store');
    Route::post('tax-management/slab', [\App\Http\Controllers\TaxManagementController::class, 'storeSlab'])->name('taxManagement.slab.store');
    Route::post('tax-management/profile', [\App\Http\Controllers\TaxManagementController::class, 'storeProfile'])->name('taxManagement.profile.store');
    Route::get('tax-management/statement/{userId}', [\App\Http\Controllers\TaxManagementController::class, 'statement'])->name('taxManagement.statement');
});

// Employee Loan Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('employee-loans', [\App\Http\Controllers\EmployeeLoanController::class, 'index'])->name('employeeLoans.index');
    Route::get('employee-loans/create', [\App\Http\Controllers\EmployeeLoanController::class, 'create'])->name('employeeLoans.create');
    Route::post('employee-loans', [\App\Http\Controllers\EmployeeLoanController::class, 'store'])->name('employeeLoans.store');
    Route::get('employee-loans/{id}', [\App\Http\Controllers\EmployeeLoanController::class, 'show'])->name('employeeLoans.show');
    Route::get('employee-loans/{id}/edit', [\App\Http\Controllers\EmployeeLoanController::class, 'edit'])->name('employeeLoans.edit');
    Route::put('employee-loans/{id}', [\App\Http\Controllers\EmployeeLoanController::class, 'update'])->name('employeeLoans.update');
    Route::patch('employee-loans/{id}', [\App\Http\Controllers\EmployeeLoanController::class, 'update']);
    Route::delete('employee-loans/{id}', [\App\Http\Controllers\EmployeeLoanController::class, 'destroy'])->name('employeeLoans.destroy');
    Route::post('employee-loans/{id}/approve', [\App\Http\Controllers\EmployeeLoanController::class, 'approve'])->name('employeeLoans.approve');
    Route::post('employee-loans/{id}/disburse', [\App\Http\Controllers\EmployeeLoanController::class, 'disburse'])->name('employeeLoans.disburse');
    Route::post('employee-loans/{id}/reject', [\App\Http\Controllers\EmployeeLoanController::class, 'reject'])->name('employeeLoans.reject');

    // Route aliases for legacy loans.* calls
    Route::get('loans', [\App\Http\Controllers\EmployeeLoanController::class, 'index'])->name('loans.index');
    Route::get('loans/create', [\App\Http\Controllers\EmployeeLoanController::class, 'create'])->name('loans.create');
    Route::post('loans', [\App\Http\Controllers\EmployeeLoanController::class, 'store'])->name('loans.store');
    Route::get('loans/{id}', [\App\Http\Controllers\EmployeeLoanController::class, 'show'])->name('loans.show');
    Route::get('loans/{id}/edit', [\App\Http\Controllers\EmployeeLoanController::class, 'edit'])->name('loans.edit');
    Route::patch('loans/{id}', [\App\Http\Controllers\EmployeeLoanController::class, 'update'])->name('loans.update');
    Route::delete('loans/{id}', [\App\Http\Controllers\EmployeeLoanController::class, 'destroy'])->name('loans.destroy');
});

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->middleware('auth');

// User's own profile (accessible to all authenticated users)
Route::get('/my-profile', [App\Http\Controllers\UserController::class, 'profile'])->middleware('auth')->name('profile');



Route::resource('trainingDetails', App\Http\Controllers\TrainingDetailController::class);
Route::get('trainingDetails/list/{user_id}', [App\Http\Controllers\TrainingDetailController::class, 'list'])->name('trainingDetails.list');

Route::resource('jobExperiences', App\Http\Controllers\JobExperienceController::class);
Route::get('jobExperiences/list/{user_id}', [App\Http\Controllers\JobExperienceController::class, 'list'])->name('jobExperiences.list');

Route::resource('educationalQualifications', App\Http\Controllers\EducationalQualificationController::class);
Route::get('educationalQualifications/list/{user_id}', [App\Http\Controllers\EducationalQualificationController::class, 'list'])->name('educationalQualifications.list');

Route::resource('nomineeInformation', App\Http\Controllers\NomineeInformationController::class);
Route::get('nomineeInformation/list/{user_id}', [App\Http\Controllers\NomineeInformationController::class, 'list'])->name('nomineeInformation.list');

Route::resource('promotionDetails', App\Http\Controllers\PromotionDetailController::class);
Route::get('promotionDetails/list/{user_id}', [App\Http\Controllers\PromotionDetailController::class, 'list'])->name('promotionDetails.list');

Route::resource('salaryIncrements', App\Http\Controllers\SalaryIncrementController::class);
Route::get('salaryIncrements/list/{user_id}', [App\Http\Controllers\SalaryIncrementController::class, 'list'])->name('salaryIncrements.list');

Route::resource('transferDetails', App\Http\Controllers\TransferDetailController::class);
Route::get('transferDetails/list/{user_id}', [App\Http\Controllers\TransferDetailController::class, 'list'])->name('transferDetails.list');

Route::resource('personalDocuments', App\Http\Controllers\PersonalDocumentController::class);
Route::get('personalDocuments/list/{user_id}', [App\Http\Controllers\PersonalDocumentController::class, 'list'])->name('personalDocuments.list');

Route::middleware(['auth', 'permission:manage_allowance_settings'])->group(function () {
    Route::resource('allowanceSettings', App\Http\Controllers\AllowanceSettingController::class);
    Route::get('allowanceSettings/list/{user_id}', [App\Http\Controllers\AllowanceSettingController::class, 'list'])->name('allowanceSettings.list');
});

Route::get('/cron/refresh-database', [App\Http\Controllers\CronController::class, 'refreshDatabase']);

// ── Smart Movement ─────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('new-movement')->name('new-movement.')->group(function () {
    // Admin
    Route::get('/', [App\Http\Controllers\NewMovementController::class, 'index'])->name('index');
    Route::get('/ta-list', [App\Http\Controllers\NewMovementController::class, 'taList'])->name('ta-list');
    Route::get('/ta-summary', [App\Http\Controllers\NewMovementController::class, 'taSummary'])->name('ta-summary');
    Route::get('/ta-summary-details', [App\Http\Controllers\NewMovementController::class, 'taSummaryDetails'])->name('ta-summary-details');
    Route::post('/ta-summary-approve', [App\Http\Controllers\NewMovementController::class, 'taSummaryApprove'])->name('ta-summary-approve');
    Route::post('/admin-approve-ta/{id}', [App\Http\Controllers\NewMovementController::class, 'adminApproveTa'])->name('admin-approve-ta');
    Route::get('/details/{id}', [App\Http\Controllers\NewMovementController::class, 'details'])->name('details');

    // Employee flow
    Route::get('/my-dashboard', [App\Http\Controllers\NewMovementController::class, 'empDashboard'])->name('my-dashboard');
    Route::get('/start', [App\Http\Controllers\NewMovementController::class, 'showStart'])->name('start');
    Route::post('/start', [App\Http\Controllers\NewMovementController::class, 'processStart'])->name('start.process');
    Route::get('/traveling', [App\Http\Controllers\NewMovementController::class, 'traveling'])->name('traveling');
    Route::post('/reached-destination', [App\Http\Controllers\NewMovementController::class, 'reachedDestination'])->name('reached-destination');
    Route::get('/start-meeting', [App\Http\Controllers\NewMovementController::class, 'showStartMeeting'])->name('start-meeting');
    Route::post('/start-meeting', [App\Http\Controllers\NewMovementController::class, 'processStartMeeting'])->name('start-meeting.process');
    Route::get('/meeting-running', [App\Http\Controllers\NewMovementController::class, 'meetingRunning'])->name('meeting-running');
    Route::post('/end-meeting', [App\Http\Controllers\NewMovementController::class, 'endMeeting'])->name('end-meeting');
    Route::get('/feedback-form', [App\Http\Controllers\NewMovementController::class, 'feedbackForm'])->name('feedback-form');
    Route::post('/submit-feedback', [App\Http\Controllers\NewMovementController::class, 'submitFeedback'])->name('submit-feedback');
    Route::get('/log-visit', [App\Http\Controllers\NewMovementController::class, 'showLogVisit'])->name('log-visit');
    Route::post('/log-visit', [App\Http\Controllers\NewMovementController::class, 'processLogVisit'])->name('log-visit.process');
    Route::get('/decision', [App\Http\Controllers\NewMovementController::class, 'decision'])->name('decision');
    Route::post('/handle-decision', [App\Http\Controllers\NewMovementController::class, 'handleDecision'])->name('handle-decision');
    Route::post('/apply-ta/{id}', [App\Http\Controllers\NewMovementController::class, 'applyTa'])->name('apply-ta');
});

// Public Career Routes
Route::get('/careers', 'RecruitmentController@careers')->name('careers.public');
Route::get('/careers/{slug}', 'RecruitmentController@careerDetails')->name('careers.details');
Route::post('/careers/{slug}/apply', 'RecruitmentController@apply')->name('careers.apply')->middleware('throttle:5,1');
Route::post('/careers/search', 'RecruitmentController@search')->name('careers.search');

// Inventory Module - Asset Management
Route::middleware(['auth', 'permission:inventory'])->prefix('admin/inventory')->name('admin.inventory.')->group(function () {
    Route::resource('asset-categories', 'Admin\Inventory\AssetCategoryController');
    Route::resource('assets', 'Admin\Inventory\AssetController');
    Route::post('assets/{id}/log', 'Admin\Inventory\AssetController@addLog')->name('assets.addLog');
    Route::resource('asset-assignments', 'Admin\Inventory\AssetAssignmentController')->except(['show', 'edit', 'update', 'destroy']);
    Route::get('asset-assignments/ajax/available', 'Admin\Inventory\AssetAssignmentController@getAvailableAssets')->name('asset-assignments.ajax.available');
    Route::get('asset-assignments/ajax/employee-assets', 'Admin\Inventory\AssetAssignmentController@getEmployeeAssets')->name('asset-assignments.ajax.employee-assets');
    Route::post('asset-assignments/ajax/assign', 'Admin\Inventory\AssetAssignmentController@assignAssetAjax')->name('asset-assignments.ajax.assign');
    Route::post('asset-assignments/ajax/remove', 'Admin\Inventory\AssetAssignmentController@removeAssetAjax')->name('asset-assignments.ajax.remove');
    Route::get('asset-assignments/{id}/return', 'Admin\Inventory\AssetAssignmentController@returnForm')->name('asset-assignments.returnForm');
    Route::post('asset-assignments/{id}/return', 'Admin\Inventory\AssetAssignmentController@processReturn')->name('asset-assignments.processReturn');
    Route::match(['get', 'post'], 'asset-logs', 'Admin\Inventory\AssetLogController@index')->name('asset-logs.index');
    Route::redirect('reports', 'reports/assets')->name('reports.index');
    Route::match(['get', 'post'], 'reports/assets', 'Admin\Inventory\InventoryReportController@assetReports')->name('reports.assets');
    Route::match(['get', 'post'], 'reports/assignments', 'Admin\Inventory\InventoryReportController@assignmentReports')->name('reports.assignments');
    Route::match(['get', 'post'], 'reports/lifecycle', 'Admin\Inventory\InventoryReportController@lifecycleReports')->name('reports.lifecycle');
    Route::match(['get', 'post'], 'reports/inventory', 'Admin\Inventory\InventoryReportController@inventoryReports')->name('reports.inventory');
});

// Maintenance Module
Route::middleware(['auth', 'permission:maintenance'])->prefix('admin/maintenance')->name('admin.maintenance.')->group(function () {
    Route::get('/', 'Admin\Maintenance\MaintenanceDashboardController@index')->name('index');
    Route::resource('vendors', 'Admin\Maintenance\VendorController');
    Route::resource('types', 'Admin\Maintenance\MaintenanceTypeController');
    Route::resource('requests', 'Admin\Maintenance\MaintenanceRequestController');
    Route::match(['get', 'post'], 'reports', 'Admin\Maintenance\MaintenanceReportController@index')->name('reports.index');
    Route::get('reports/export', 'Admin\Maintenance\MaintenanceReportController@export')->name('reports.export');
});

// Pension Module
Route::middleware(['auth', 'permission:pension'])->prefix('admin/pension')->name('admin.pension.')->group(function () {
    Route::get('policies/approvals', 'Admin\Pension\PolicyController@approvalList')->name('policies.approvals');
    Route::get('policies/audit-logs', 'Admin\Pension\PolicyController@auditLogs')->name('policies.audit-logs');
    Route::get('policies/assignments', 'Admin\Pension\PolicyController@assignmentIndex')->name('policies.assignments');
    Route::post('policies/assignments/store', 'Admin\Pension\PolicyController@assignmentStore')->name('policies.assignments.store');
    Route::get('policies/{id}/clone', 'Admin\Pension\PolicyController@clonePolicy')->name('policies.clone');
    Route::get('policies/{id}/versions', 'Admin\Pension\PolicyController@versionHistory')->name('policies.versions');
    Route::post('policies/{id}/approve', 'Admin\Pension\PolicyController@approvePolicy')->name('policies.approve');
    Route::post('policies/{id}/reject', 'Admin\Pension\PolicyController@rejectPolicy')->name('policies.reject');
    Route::resource('policies', 'Admin\Pension\PolicyController');
    
    Route::post('eligibility/run-check', 'Admin\Pension\EligibilityController@runCheck')->name('eligibility.run-check');
    Route::match(['get', 'post'], 'eligibility', 'Admin\Pension\EligibilityController@index')->name('eligibility.index');
    Route::resource('eligibility', 'Admin\Pension\EligibilityController')->except(['index', 'store']);
    
    Route::post('calculations/process', 'Admin\Pension\CalculationController@process')->name('calculations.process');
    Route::match(['get', 'post'], 'calculations', 'Admin\Pension\CalculationController@index')->name('calculations.index');
    Route::resource('calculations', 'Admin\Pension\CalculationController')->except(['index', 'store']);
    
    Route::post('disbursements/{id}/pay', 'Admin\Pension\DisbursementController@pay')->name('disbursements.pay');
    Route::get('disbursements/{id}/payslip', 'Admin\Pension\DisbursementController@downloadPayslip')->name('disbursements.payslip');
    Route::post('disbursements/process', 'Admin\Pension\DisbursementController@process')->name('disbursements.process');
    Route::resource('disbursements', 'Admin\Pension\DisbursementController');

    // Arrear Bill System
    Route::get('arrear-bills', 'Admin\Pension\ArrearBillController@index')->name('arrear-bills.index');
    Route::get('arrear-bills/create', 'Admin\Pension\ArrearBillController@create')->name('arrear-bills.create');
    Route::post('arrear-bills', 'Admin\Pension\ArrearBillController@store')->name('arrear-bills.store');
    Route::post('arrear-bills/allocate', 'Admin\Pension\ArrearBillController@allocate')->name('arrear-bills.allocate');
    Route::post('arrear-bills/{id}', 'Admin\Pension\ArrearBillController@update')->name('arrear-bills.update');
    Route::delete('arrear-bills/{id}', 'Admin\Pension\ArrearBillController@destroy')->name('arrear-bills.destroy');

// Pension Reports
    Route::match(['get', 'post'], 'reports', 'Admin\Pension\ReportController@index')->name('reports.index');
});

// Provident Fund (PF) Module
Route::middleware(['auth'])->prefix('pf')->name('pf.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\PfDashboardController::class, 'index'])->name('dashboard');
    
    // PF Schemes (Settings)
    Route::resource('schemes', \App\Http\Controllers\PfSchemeController::class);
    
    // PF Employee Accounts
    Route::get('employees', [\App\Http\Controllers\PfEmployeeController::class, 'index'])->name('employees.index');
    Route::post('employees/{id}/generate-account', [\App\Http\Controllers\PfEmployeeController::class, 'generateAccount'])->name('employees.generate_account');

    // PF Contributions
    Route::get('contributions', [\App\Http\Controllers\PfContributionController::class, 'index'])->name('contributions.index');
    Route::post('contributions', [\App\Http\Controllers\PfContributionController::class, 'store'])->name('contributions.store');
    Route::post('contributions/{contribution}/process', [\App\Http\Controllers\PfContributionController::class, 'process'])->name('contributions.process');
    
    // PF Loans
    Route::get('loans', [\App\Http\Controllers\PfLoanController::class, 'index'])->name('loans.index');
    Route::get('loans/create', [\App\Http\Controllers\PfLoanController::class, 'create'])->name('loans.create');
    Route::post('loans', [\App\Http\Controllers\PfLoanController::class, 'store'])->name('loans.store');
    Route::post('loans/{loan}/approve', [\App\Http\Controllers\PfLoanController::class, 'approve'])->name('loans.approve');
    Route::post('loans/{loan}/disburse', [\App\Http\Controllers\PfLoanController::class, 'disburse'])->name('loans.disburse');
    
    // PF Withdrawals
    Route::get('withdrawals', [\App\Http\Controllers\PfWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('withdrawals/create', [\App\Http\Controllers\PfWithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('withdrawals', [\App\Http\Controllers\PfWithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::post('withdrawals/{withdrawal}/approve', [\App\Http\Controllers\PfWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('withdrawals/{withdrawal}/disburse', [\App\Http\Controllers\PfWithdrawalController::class, 'disburse'])->name('withdrawals.disburse');
    
    // PF Reports & Analytics
    Route::get('reports/yearly', [\App\Http\Controllers\PfReportController::class, 'yearly'])->name('reports.yearly');
    Route::post('reports/yearly/calculate', [\App\Http\Controllers\PfReportController::class, 'calculateInterest'])->name('reports.calculate_interest');
    Route::get('reports/analytics', [\App\Http\Controllers\PfReportController::class, 'analytics'])->name('reports.analytics');
    Route::get('reports/statement', [\App\Http\Controllers\PfReportController::class, 'statement'])->name('reports.statement');
});
