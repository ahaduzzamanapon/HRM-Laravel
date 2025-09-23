<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


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
Route::get('attendance/report', [App\Http\Controllers\AttendanceProcessController::class, 'getReportData'])->name('attendance.report');
Route::post('attendance/manual', [App\Http\Controllers\AttendanceProcessController::class, 'storeManualAttendance'])->name('attendance.manual.store');

Route::post('leave-applications/{id}/first-approve', [App\Http\Controllers\LeaveApplicationController::class, 'firstLevelApprove'])->name('leaveApplications.first.approve');
Route::post('leave-applications/{id}/final-approve', [App\Http\Controllers\LeaveApplicationController::class, 'finalApprove'])->name('leaveApplications.final.approve');
Route::post('leave-applications/{id}/reject', [App\Http\Controllers\LeaveApplicationController::class, 'reject'])->name('leaveApplications.reject');


Route::get('/', function () {
    return view('index');
})->middleware('auth');



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






