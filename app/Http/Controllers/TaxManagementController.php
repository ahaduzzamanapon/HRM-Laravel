<?php

namespace App\Http\Controllers;

use App\Models\EmployeeTaxProfile;
use App\Models\TaxFiscalYear;
use App\Models\TaxSlab;
use App\Models\TaxAdjustment;
use App\Models\User;
use Illuminate\Http\Request;
use Flash;

class TaxManagementController extends Controller
{
    public function index(Request $request)
    {
        $fiscalYears = TaxFiscalYear::orderBy('id', 'desc')->get();
        $activeYear = TaxFiscalYear::where('is_active', true)->first();

        $slabs = TaxSlab::with('fiscalYear')->orderBy('slab_order', 'asc')->get();

        $query = EmployeeTaxProfile::with(['user.branch', 'user.department']);
        
        // Branch isolation for non-super-admins
        if (!isSuperAdmin()) {
            $branchId = userBranchId();
            if ($branchId) {
                $query->whereHas('user', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('emp_id', 'like', "%{$search}%");
            });
        }

        $taxProfiles = $query->paginate(15);

        // Fetch employees for dropdown
        $empQuery = User::query();
        if (!isSuperAdmin()) {
            applyBranchScope($empQuery, 'branch_id');
        }
        $employees = $empQuery->where(function($q) {
            $q->whereNull('group_id')->orWhere('group_id', '!=', 1);
        })->orderBy('name', 'asc')->get();

        return view('tax_management.index', compact('fiscalYears', 'activeYear', 'slabs', 'taxProfiles', 'employees'));
    }

    public function storeFiscalYear(Request $request)
    {
        $request->validate([
            'year_name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($request->has('is_active') && $request->is_active) {
            TaxFiscalYear::query()->update(['is_active' => false]);
        }

        TaxFiscalYear::create([
            'year_name' => $request->year_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        Flash::success('Fiscal Year saved successfully.');
        return redirect()->route('taxManagement.index');
    }

    public function storeSlab(Request $request)
    {
        $request->validate([
            'min_income' => 'required|numeric|min:0',
            'max_income' => 'required|numeric|gte:min_income',
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        $activeYear = TaxFiscalYear::where('is_active', true)->first();

        TaxSlab::create([
            'fiscal_year_id' => $activeYear ? $activeYear->id : null,
            'gender_category' => $request->input('gender_category') ?: 'All',
            'min_income' => (float) $request->min_income,
            'max_income' => (float) $request->max_income,
            'tax_rate' => (float) $request->tax_rate,
            'fixed_amount' => (float) ($request->fixed_amount ?: 0),
            'slab_order' => (int) ($request->slab_order ?: 1),
            'status' => 'Active',
        ]);

        Flash::success('Tax Slab created successfully.');
        return redirect()->route('taxManagement.index');
    }

    public function storeProfile(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tin_number' => 'nullable|string',
        ]);

        $monthlyGross = (float) ($request->input('monthly_gross', 0));
        $yearlyGross = $monthlyGross * 12;
        $investment = (float) ($request->input('investment_amount', 0));

        // Rebate calculation (15% of eligible investment)
        $rebate = $investment * 0.15;
        
        // Simple Slab Tax Estimation
        $taxableIncome = max(0, $yearlyGross - 350000); // 3.5 Lakh tax-free threshold
        $yearlyTax = max(0, ($taxableIncome * 0.05) - $rebate);
        $monthlyDeduction = round($yearlyTax / 12, 2);

        EmployeeTaxProfile::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'tin_number' => $request->tin_number,
                'tax_circle' => $request->tax_circle,
                'tax_zone' => $request->tax_zone,
                'tax_region' => $request->tax_region,
                'filing_status' => $request->input('filing_status', 'Registered'),
                'investment_amount' => $investment,
                'rebate_claimed' => $rebate,
                'yearly_tax_estimate' => $yearlyTax,
                'monthly_tax_deduction' => $monthlyDeduction,
            ]
        );

        Flash::success('Employee Tax Profile saved successfully.');
        return redirect()->route('taxManagement.index');
    }

    public function statement($userId)
    {
        $user = User::with(['branch', 'department', 'designation'])->findOrFail($userId);
        
        if (!isSuperAdmin()) {
            checkBranchAccess($user->branch_id);
        }

        $profile = EmployeeTaxProfile::where('user_id', $user->id)->first();
        $adjustments = TaxAdjustment::where('user_id', $user->id)->get();
        $activeYear = TaxFiscalYear::where('is_active', true)->first();

        return view('tax_management.statement', compact('user', 'profile', 'adjustments', 'activeYear'));
    }
}
