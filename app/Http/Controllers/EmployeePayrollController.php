<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payroll;
use App\Models\EmployeeBonus;
use App\Models\EmployeeTaxProfile;
use App\Models\TaxFiscalYear;
use App\Models\TaxAdjustment;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeePayrollController extends Controller
{
    /**
     * Display the employee's payroll portal: Salary Sheets, Payslips, Tax Records, and Bonus Records.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Fetch Processed Payroll Records for the logged-in employee
        $payrolls = Payroll::where('user_id', $user->id)
            ->orderBy('salary_month', 'desc')
            ->get();

        // 2. Fetch Tax Profile & History for the logged-in employee
        $taxProfile = EmployeeTaxProfile::where('user_id', $user->id)->first();
        $activeFiscalYear = TaxFiscalYear::where('is_active', true)->first();
        $taxAdjustments = TaxAdjustment::where('user_id', $user->id)->get();
        $taxDeductions = Payroll::where('user_id', $user->id)
            ->where('tax_deduct', '>', 0)
            ->orderBy('salary_month', 'desc')
            ->get();

        // 3. Fetch Bonus Records for the logged-in employee
        $bonuses = EmployeeBonus::with('bonusSetting')
            ->where('user_id', $user->id)
            ->orderBy('bonus_month', 'desc')
            ->get();

        $latestPayroll = $payrolls->first();

        return view('payroll.my_payroll', compact(
            'user',
            'payrolls',
            'taxProfile',
            'activeFiscalYear',
            'taxAdjustments',
            'taxDeductions',
            'bonuses',
            'latestPayroll'
        ));
    }

    /**
     * View or print Payslip for logged-in employee.
     */
    public function myPayslip(Request $request)
    {
        $user = Auth::user();
        $salaryMonth = $request->input('salary_month');

        $query = Payroll::select(
            'payrolls.*',
            'users.emp_id as emp_id',
            'users.name',
            'users.last_name',
            'users.basic_salary',
            'users.account_no',
            'users.emp_type',
            'designations.desi_name',
            'salary_grades.*',
            'banksetups.*'
        )
            ->join('users', 'payrolls.user_id', '=', 'users.id', 'LEFT')
            ->join('designations', 'users.designation_id', '=', 'designations.id', 'LEFT')
            ->join('salary_grades', 'users.salary_grade_id', '=', 'salary_grades.id', 'LEFT')
            ->join('banksetups', 'users.bank_id', '=', 'banksetups.id', 'LEFT')
            ->where('payrolls.user_id', $user->id);

        if ($salaryMonth) {
            $query->where('payrolls.salary_month', date('Y-m-01', strtotime($salaryMonth)));
        }

        $salary_reports = $query->orderBy('payrolls.salary_month', 'desc')->get();
        $salary_month = $salaryMonth ?: date('Y-m');
        $siteSetting = SiteSetting::first();

        return view('payroll.payslip', compact('salary_reports', 'salary_month', 'siteSetting'));
    }

    /**
     * View or print Salary Sheet for logged-in employee.
     */
    public function mySalarySheet(Request $request)
    {
        $user = Auth::user();
        $salaryMonth = $request->input('salary_month');

        $query = Payroll::select(
            'payrolls.*',
            'users.emp_id as emp_id',
            'users.name',
            'users.last_name',
            'users.basic_salary',
            'users.account_no',
            'users.emp_type',
            'designations.desi_name',
            'salary_grades.*',
            'banksetups.*'
        )
            ->join('users', 'payrolls.user_id', '=', 'users.id', 'LEFT')
            ->join('designations', 'users.designation_id', '=', 'designations.id', 'LEFT')
            ->join('salary_grades', 'users.salary_grade_id', '=', 'salary_grades.id', 'LEFT')
            ->join('banksetups', 'users.bank_id', '=', 'banksetups.id', 'LEFT')
            ->where('payrolls.user_id', $user->id);

        if ($salaryMonth) {
            $query->where('payrolls.salary_month', date('Y-m-01', strtotime($salaryMonth)));
        }

        $salary_reports = $query->orderBy('payrolls.salary_month', 'desc')->get();
        $salary_month = $salaryMonth ?: date('Y-m');
        $siteSetting = SiteSetting::first();

        return view('payroll.salary_report', compact('salary_reports', 'salary_month', 'siteSetting'));
    }

    /**
     * View or print Banking Sector Tax Certificate for logged-in employee.
     */
    public function myTax(Request $request)
    {
        $user = User::with(['branch', 'department', 'designation', 'salaryGrade', 'bankSetups'])->findOrFail(Auth::id());
        $salaryMonth = $request->input('salary_month');

        $taxProfile = EmployeeTaxProfile::where('user_id', $user->id)->first();
        $activeYear = TaxFiscalYear::where('is_active', true)->first();

        // Query specific payroll or latest
        $payrollQuery = Payroll::where('user_id', $user->id);
        if ($salaryMonth) {
            $payrollQuery->where('salary_month', date('Y-m-01', strtotime($salaryMonth)));
        }
        $payroll = $payrollQuery->orderBy('salary_month', 'desc')->first();

        $siteSetting = SiteSetting::first();

        return view('payroll.employee_tax_certificate', compact(
            'user',
            'taxProfile',
            'activeYear',
            'payroll',
            'salaryMonth',
            'siteSetting'
        ));
    }
}
