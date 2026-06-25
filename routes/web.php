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

include 'web_builder.php';
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

Auth::routes();

Route::get('/payroll', [App\Http\Controllers\PayrollController::class, 'index'])->name('payroll.index');
Route::post('payroll/process', [App\Http\Controllers\PayrollController::class, 'process'])->name('payroll.process');


Route::post('payroll/salary-report', [App\Http\Controllers\PayrollController::class, 'salaryReport'])->name('payroll.salarySheet');

Route::post('payroll/payslip', [App\Http\Controllers\PayrollController::class, 'payslip'])->name('payroll.payslip');
Route::post('payroll/tax', [App\Http\Controllers\PayrollController::class, 'tax'])->name('payroll.tax');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');


Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('password/otp', [ResetPasswordController::class, 'showOtpForm'])->name('password.otp');
Route::post('password/otp', [ResetPasswordController::class, 'verifyOtp'])->name('password.verify.otp');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::patch('users/update-salary/{id}', [App\Http\Controllers\UserController::class, 'updateSalary'])->name('users.updateSalary');

Route::get('attendance/process', [App\Http\Controllers\AttendanceProcessController::class, 'index'])->name('attendance.process.index');
Route::post('attendance/process', [App\Http\Controllers\AttendanceProcessController::class, 'process'])->name('attendance.process.store');
Route::get('attendance/filter', [App\Http\Controllers\AttendanceProcessController::class, 'filterUsers'])->name('attendance.filter');
Route::post('attendance/report', [App\Http\Controllers\AttendanceProcessController::class, 'getReportData'])->name('attendance.report');
Route::post('attendance/manual', [App\Http\Controllers\AttendanceProcessController::class, 'storeManualAttendance'])->name('attendance.manual.store');
Route::get('my-attendance', [App\Http\Controllers\AttendanceProcessController::class, 'myAttendance'])->name('attendance.my');

// dashboard report data
Route::post('attendance/daily-report', [App\Http\Controllers\AttendanceProcessController::class, 'getDailyReportData'])->name('attendance.daily-report');




Route::post('leave-applications/{id}/first-approve', [App\Http\Controllers\LeaveApplicationController::class, 'firstLevelApprove'])->name('leaveApplications.first.approve');
Route::post('leave-applications/{id}/final-approve', [App\Http\Controllers\LeaveApplicationController::class, 'finalApprove'])->name('leaveApplications.final.approve');
Route::post('leave-applications/{id}/reject', [App\Http\Controllers\LeaveApplicationController::class, 'reject'])->name('leaveApplications.reject');


Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->middleware('auth');



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

Route::resource('allowanceSettings', App\Http\Controllers\AllowanceSettingController::class);
Route::get('allowanceSettings/list/{user_id}', [App\Http\Controllers\AllowanceSettingController::class, 'list'])->name('allowanceSettings.list');

Route::get('/cron/refresh-database', [App\Http\Controllers\CronController::class, 'refreshDatabase']);

// ── Smart Movement ─────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('new-movement')->name('new-movement.')->group(function () {
    // Admin
    Route::get('/', [App\Http\Controllers\NewMovementController::class, 'index'])->name('index');
    Route::get('/ta-list', [App\Http\Controllers\NewMovementController::class, 'taList'])->name('ta-list');
    Route::get('/ta-summary', [App\Http\Controllers\NewMovementController::class, 'taSummary'])->name('ta-summary');
    Route::get('/ta-summary-details', [App\Http\Controllers\NewMovementController::class, 'taSummaryDetails'])->name('ta-summary-details');
    Route::post('/ta-summary-approve', [App\Http\Controllers\NewMovementController::class, 'taSummaryApprove'])->name('ta-summary-approve');
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
Route::middleware('auth')->prefix('admin/inventory')->name('admin.inventory.')->group(function () {
    Route::resource('asset-categories', 'Admin\Inventory\AssetCategoryController');
    Route::resource('assets', 'Admin\Inventory\AssetController');
    Route::post('assets/{id}/log', 'Admin\Inventory\AssetController@addLog')->name('assets.addLog');
    Route::resource('asset-assignments', 'Admin\Inventory\AssetAssignmentController')->except(['show', 'edit', 'update', 'destroy']);
    Route::get('asset-assignments/{id}/return', 'Admin\Inventory\AssetAssignmentController@returnForm')->name('asset-assignments.returnForm');
    Route::post('asset-assignments/{id}/return', 'Admin\Inventory\AssetAssignmentController@processReturn')->name('asset-assignments.processReturn');
    Route::match(['get', 'post'], 'asset-logs', 'Admin\Inventory\AssetLogController@index')->name('asset-logs.index');
    Route::redirect('reports', 'reports/assets')->name('reports.index');
    Route::get('reports/assets', 'Admin\Inventory\InventoryReportController@assetReports')->name('reports.assets');
    Route::get('reports/assignments', 'Admin\Inventory\InventoryReportController@assignmentReports')->name('reports.assignments');
    Route::get('reports/lifecycle', 'Admin\Inventory\InventoryReportController@lifecycleReports')->name('reports.lifecycle');
    Route::get('reports/inventory', 'Admin\Inventory\InventoryReportController@inventoryReports')->name('reports.inventory');
});

// Maintenance Module
Route::middleware('auth')->prefix('admin/maintenance')->name('admin.maintenance.')->group(function () {
    Route::resource('vendors', 'Admin\Maintenance\VendorController');
    Route::resource('types', 'Admin\Maintenance\MaintenanceTypeController');
    Route::resource('requests', 'Admin\Maintenance\MaintenanceRequestController');
    Route::get('reports', 'Admin\Maintenance\MaintenanceReportController@index')->name('reports.index');
    Route::get('reports/export', 'Admin\Maintenance\MaintenanceReportController@export')->name('reports.export');
});
