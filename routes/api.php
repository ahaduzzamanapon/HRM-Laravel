<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\BranchApiController;
use App\Http\Controllers\Api\DepartmentApiController;
use App\Http\Controllers\Api\DesignationApiController;
use App\Http\Controllers\Api\ShiftApiController;
use App\Http\Controllers\Api\HolydayApiController;
use App\Http\Controllers\Api\LeaveTypeApiController;
use App\Http\Controllers\Api\AllowanceSettingApiController;
use App\Http\Controllers\Api\SalaryGradeApiController;
use App\Http\Controllers\Api\BankSetupApiController;
use App\Http\Controllers\Api\TaxSetupApiController;
use App\Http\Controllers\Api\LoanTypeApiController;
use App\Http\Controllers\Api\RoleAndPermissionApiController;
use App\Http\Controllers\Api\TrainingDetailApiController;
use App\Http\Controllers\Api\JobExperienceApiController;
use App\Http\Controllers\Api\EducationalQualificationApiController;
use App\Http\Controllers\Api\NomineeInformationApiController;
use App\Http\Controllers\Api\PromotionDetailApiController;
use App\Http\Controllers\Api\SalaryIncrementApiController;
use App\Http\Controllers\Api\TransferDetailApiController;
use App\Http\Controllers\Api\PersonalDocumentApiController;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\BiometricDeviceApiController;
use App\Http\Controllers\Api\BiometricAttendanceLogApiController;
use App\Http\Controllers\Api\BiometricCommandApiController;
use App\Http\Controllers\Api\LeaveApplicationApiController;
use App\Http\Controllers\Api\MovementApiController;
use App\Http\Controllers\Api\PayrollApiController;
use App\Http\Controllers\Api\ProvidentFundApiController;
use App\Http\Controllers\Api\ProvidentFundSettingApiController;
use App\Http\Controllers\Api\ProvidentFundLoanApiController;
use App\Http\Controllers\Api\ProvidentFundLoanRepaymentApiController;
use App\Http\Controllers\Api\LoanApiController;
use App\Http\Controllers\Api\LoanRepaymentApiController;
use App\Http\Controllers\Api\ChildAllowanceApiController;
use App\Http\Controllers\Api\MedicalSupportApiController;
use App\Http\Controllers\Api\FuneralSupportApiController;
use App\Http\Controllers\Api\EmployeeChildrenEducationSupportApiController;
use App\Http\Controllers\Api\DepartmentalCaseApiController;
use App\Http\Controllers\Api\PenaltyApiController;
use App\Http\Controllers\Api\RewardingApiController;
use App\Http\Controllers\Api\InnovationApiController;
use App\Http\Controllers\Api\NoticeApiController;
use App\Http\Controllers\Api\SiteSettingApiController;

/*
|--------------------------------------------------------------------------
| HRM API Routes — v1
|--------------------------------------------------------------------------
| Base URL: /api/v1/
| Auth: Laravel Sanctum Bearer Token
| Login to get a token, then pass it as: Authorization: Bearer {token}
*/

Route::prefix('v1')->group(function () {

    /*-------------------------------------------------------------------
    | Public Routes (no auth required)
    -------------------------------------------------------------------*/
    Route::post('login', [AuthApiController::class, 'login']);

    /*-------------------------------------------------------------------
    | Protected Routes (Sanctum auth required)
    -------------------------------------------------------------------*/
    Route::middleware('auth:sanctum')->group(function () {

        // ── Auth ──────────────────────────────────────────────────────
        Route::post('logout', [AuthApiController::class, 'logout']);
        Route::get('profile', [AuthApiController::class, 'profile']);
        Route::post('change-password', [AuthApiController::class, 'changePassword']);

        // ── Dashboard ─────────────────────────────────────────────────
        Route::get('dashboard', [DashboardApiController::class, 'index']);
        Route::get('my-dashboard', [DashboardApiController::class, 'myDashboard']);

        // ── Master Data ───────────────────────────────────────────────
        Route::apiResource('branches', BranchApiController::class);
        Route::apiResource('departments', DepartmentApiController::class);
        Route::apiResource('designations', DesignationApiController::class);
        Route::apiResource('shifts', ShiftApiController::class);
        Route::apiResource('holidays', HolydayApiController::class);
        Route::apiResource('leave-types', LeaveTypeApiController::class);
        Route::apiResource('allowance-settings', AllowanceSettingApiController::class);
        Route::apiResource('salary-grades', SalaryGradeApiController::class);
        Route::apiResource('bank-setups', BankSetupApiController::class);
        Route::apiResource('tax-setups', TaxSetupApiController::class);
        Route::apiResource('loan-types', LoanTypeApiController::class);
        Route::apiResource('roles', RoleAndPermissionApiController::class);
        Route::get('permissions', [RoleAndPermissionApiController::class, 'permissions']);

        // ── Employees ─────────────────────────────────────────────────
        Route::patch('users/{id}/salary', [UserApiController::class, 'updateSalary']);
        Route::apiResource('users', UserApiController::class);

        // Employee sub-records (list by user_id + CRUD)
        Route::get('training-details/by-user/{userId}', [TrainingDetailApiController::class, 'listByUser']);
        Route::apiResource('training-details', TrainingDetailApiController::class);

        Route::get('job-experiences/by-user/{userId}', [JobExperienceApiController::class, 'listByUser']);
        Route::apiResource('job-experiences', JobExperienceApiController::class);

        Route::get('educational-qualifications/by-user/{userId}', [EducationalQualificationApiController::class, 'listByUser']);
        Route::apiResource('educational-qualifications', EducationalQualificationApiController::class);

        Route::get('nominee-information/by-user/{userId}', [NomineeInformationApiController::class, 'listByUser']);
        Route::apiResource('nominee-information', NomineeInformationApiController::class);

        Route::get('promotion-details/by-user/{userId}', [PromotionDetailApiController::class, 'listByUser']);
        Route::apiResource('promotion-details', PromotionDetailApiController::class);

        Route::get('salary-increments/by-user/{userId}', [SalaryIncrementApiController::class, 'listByUser']);
        Route::apiResource('salary-increments', SalaryIncrementApiController::class);

        Route::get('transfer-details/by-user/{userId}', [TransferDetailApiController::class, 'listByUser']);
        Route::apiResource('transfer-details', TransferDetailApiController::class);

        Route::get('personal-documents/by-user/{userId}', [PersonalDocumentApiController::class, 'listByUser']);
        Route::apiResource('personal-documents', PersonalDocumentApiController::class);

        // ── Attendance ────────────────────────────────────────────────
        Route::prefix('attendance')->group(function () {
            Route::get('my', [AttendanceApiController::class, 'myAttendance']);
            Route::get('report', [AttendanceApiController::class, 'report']);
            Route::get('filter', [AttendanceApiController::class, 'filterUsers']);
            Route::get('daily-report', [AttendanceApiController::class, 'dailyReport']);
            Route::post('manual', [AttendanceApiController::class, 'storeManual']);
            Route::post('process', [AttendanceApiController::class, 'process']);
        });

        // Biometric
        Route::apiResource('biometric-devices', BiometricDeviceApiController::class);
        Route::get('biometric-logs', [BiometricAttendanceLogApiController::class, 'index']);
        Route::get('biometric-logs/{id}', [BiometricAttendanceLogApiController::class, 'show']);
        Route::apiResource('biometric-commands', BiometricCommandApiController::class)->except(['update']);

        // ── Leave & Movement ──────────────────────────────────────────
        Route::post('leave-applications/{id}/first-approve', [LeaveApplicationApiController::class, 'firstApprove']);
        Route::post('leave-applications/{id}/final-approve', [LeaveApplicationApiController::class, 'finalApprove']);
        Route::post('leave-applications/{id}/reject', [LeaveApplicationApiController::class, 'reject']);
        Route::apiResource('leave-applications', LeaveApplicationApiController::class);

        Route::apiResource('movements', MovementApiController::class);

        // ── Payroll ───────────────────────────────────────────────────
        Route::prefix('payroll')->group(function () {
            Route::get('/', [PayrollApiController::class, 'index']);
            Route::get('{id}', [PayrollApiController::class, 'show']);
            Route::post('payslip', [PayrollApiController::class, 'payslip']);
            Route::post('salary-report', [PayrollApiController::class, 'salaryReport']);
            Route::post('tax-report', [PayrollApiController::class, 'taxReport']);
        });

        // ── Provident Fund ────────────────────────────────────────────
        Route::get('provident-fund', [ProvidentFundApiController::class, 'index']);
        Route::get('provident-fund/{id}', [ProvidentFundApiController::class, 'show']);
        Route::get('provident-fund/balance/{userId}', [ProvidentFundApiController::class, 'balance']);
        Route::get('provident-fund-settings', [ProvidentFundSettingApiController::class, 'index']);
        Route::post('provident-fund-settings', [ProvidentFundSettingApiController::class, 'store']);
        Route::apiResource('provident-fund-loans', ProvidentFundLoanApiController::class);
        Route::apiResource('provident-fund-repayments', ProvidentFundLoanRepaymentApiController::class)->except(['update']);

        // ── Loans ─────────────────────────────────────────────────────
        Route::apiResource('loans', LoanApiController::class);
        Route::apiResource('loan-repayments', LoanRepaymentApiController::class)->except(['update']);

        // ── Child Allowances ──────────────────────────────────────────
        Route::apiResource('child-allowances', ChildAllowanceApiController::class);

        // ── Welfare ───────────────────────────────────────────────────
        Route::apiResource('medical-supports', MedicalSupportApiController::class);
        Route::apiResource('funeral-supports', FuneralSupportApiController::class);
        Route::apiResource('education-supports', EmployeeChildrenEducationSupportApiController::class);

        // ── HR Actions ────────────────────────────────────────────────
        Route::apiResource('departmental-cases', DepartmentalCaseApiController::class);
        Route::apiResource('penalties', PenaltyApiController::class);
        Route::apiResource('rewardings', RewardingApiController::class);
        Route::apiResource('innovations', InnovationApiController::class);
        Route::apiResource('notices', NoticeApiController::class);

        // ── Site Settings ─────────────────────────────────────────────
        Route::get('site-settings', [SiteSettingApiController::class, 'index']);
        Route::post('site-settings', [SiteSettingApiController::class, 'update']);
    });
});
