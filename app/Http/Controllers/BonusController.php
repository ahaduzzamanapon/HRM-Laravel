<?php

namespace App\Http\Controllers;

use App\Models\BonusSetting;
use App\Models\EmployeeBonus;
use App\Models\Branch;
use App\Services\BonusCalculatorService;
use Illuminate\Http\Request;
use Flash;
use Carbon\Carbon;

class BonusController extends Controller
{
    protected $calculatorService;

    public function __construct(BonusCalculatorService $calculatorService)
    {
        $this->calculatorService = $calculatorService;
    }

    /**
     * Display listing of bonus rules.
     */
    public function index(Request $request)
    {
        $query = BonusSetting::with('branch')->orderBy('created_at', 'desc');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        applyBranchScope($query, 'branch_id');

        $bonusSettings = $query->paginate(15);

        $branchesQuery = Branch::query();
        applyBranchScope($branchesQuery, 'id');
        $branches = $branchesQuery->pluck('branch_name', 'id');

        return view('bonuses.index', compact('bonusSettings', 'branches'));
    }

    /**
     * Show form for creating new bonus rule.
     */
    public function create()
    {
        $branchesQuery = Branch::query();
        applyBranchScope($branchesQuery, 'id');
        $branches = $branchesQuery->pluck('branch_name', 'id');

        return view('bonuses.create', compact('branches'));
    }

    /**
     * Store new bonus rule.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'bonus_type' => 'required|in:percentage,fixed',
            'calculation_base' => 'required|in:basic_salary,gross_salary,fixed',
            'amount_percentage' => 'required|numeric|min:0',
            'bonus_month' => 'required|date_format:Y-m',
        ]);

        $input = $request->all();
        $input['bonus_month'] = Carbon::parse($request->bonus_month . '-01')->format('Y-m-d');

        if (!isSuperAdmin()) {
            $input['branch_id'] = userBranchId();
        }

        BonusSetting::create($input);

        Flash::success('Bonus setting rule created successfully.');
        return redirect(route('bonuses.index'));
    }

    /**
     * Show form for editing bonus rule.
     */
    public function edit($id)
    {
        $bonusSetting = BonusSetting::find($id);

        if (!$bonusSetting) {
            Flash::error('Bonus setting not found.');
            return redirect(route('bonuses.index'));
        }

        checkBranchAccess($bonusSetting->branch_id);

        $branchesQuery = Branch::query();
        applyBranchScope($branchesQuery, 'id');
        $branches = $branchesQuery->pluck('branch_name', 'id');

        return view('bonuses.edit', compact('bonusSetting', 'branches'));
    }

    /**
     * Update bonus rule.
     */
    public function update(Request $request, $id)
    {
        $bonusSetting = BonusSetting::find($id);

        if (!$bonusSetting) {
            Flash::error('Bonus setting not found.');
            return redirect(route('bonuses.index'));
        }

        checkBranchAccess($bonusSetting->branch_id);

        $request->validate([
            'title' => 'required|string|max:255',
            'bonus_type' => 'required|in:percentage,fixed',
            'calculation_base' => 'required|in:basic_salary,gross_salary,fixed',
            'amount_percentage' => 'required|numeric|min:0',
            'bonus_month' => 'required|date_format:Y-m',
        ]);

        $input = $request->all();
        $input['bonus_month'] = Carbon::parse($request->bonus_month . '-01')->format('Y-m-d');

        if (!isSuperAdmin()) {
            $input['branch_id'] = userBranchId();
        }

        $bonusSetting->update($input);

        Flash::success('Bonus setting updated successfully.');
        return redirect(route('bonuses.index'));
    }

    /**
     * Delete bonus rule.
     */
    public function destroy($id)
    {
        $bonusSetting = BonusSetting::find($id);

        if (!$bonusSetting) {
            Flash::error('Bonus setting not found.');
            return redirect(route('bonuses.index'));
        }

        checkBranchAccess($bonusSetting->branch_id);

        $bonusSetting->delete();

        Flash::success('Bonus setting deleted successfully.');
        return redirect(route('bonuses.index'));
    }

    /**
     * Show preview form for processing bonus.
     */
    public function processForm($id)
    {
        $bonusSetting = BonusSetting::with('branch')->find($id);

        if (!$bonusSetting) {
            Flash::error('Bonus setting not found.');
            return redirect(route('bonuses.index'));
        }

        checkBranchAccess($bonusSetting->branch_id);

        $eligibleUsers = $this->calculatorService->calculateForSetting($bonusSetting);

        return view('bonuses.process', compact('bonusSetting', 'eligibleUsers'));
    }

    /**
     * Save processed bonus disbursements.
     */
    public function processStore(Request $request, $id)
    {
        $bonusSetting = BonusSetting::find($id);

        if (!$bonusSetting) {
            Flash::error('Bonus setting not found.');
            return redirect(route('bonuses.index'));
        }

        checkBranchAccess($bonusSetting->branch_id);

        $selectedUserIds = $request->input('selected_users', []);
        $baseAmounts = $request->input('base_amounts', []);
        $bonusAmounts = $request->input('bonus_amounts', []);

        if (empty($selectedUserIds)) {
            Flash::error('Please select at least one employee to disburse bonus.');
            return redirect()->back();
        }

        foreach ($selectedUserIds as $userId) {
            $baseAmt = isset($baseAmounts[$userId]) ? (float)$baseAmounts[$userId] : 0;
            $bonusAmt = isset($bonusAmounts[$userId]) ? (float)$bonusAmounts[$userId] : 0;

            $user = \App\Models\User::find($userId);
            $userBranchId = $user ? $user->branch_id : $bonusSetting->branch_id;

            EmployeeBonus::updateOrCreate(
                [
                    'bonus_setting_id' => $bonusSetting->id,
                    'user_id' => $userId,
                ],
                [
                    'branch_id' => $userBranchId,
                    'bonus_month' => $bonusSetting->bonus_month,
                    'base_amount' => $baseAmt,
                    'bonus_amount' => $bonusAmt,
                    'payment_status' => 'pending',
                ]
            );
        }

        $bonusSetting->update(['status' => 'processed']);

        Flash::success('Bonus processed and generated successfully for ' . count($selectedUserIds) . ' employees.');
        return redirect(route('bonuses.disbursements'));
    }

    /**
     * Display list of employee bonus disbursements.
     */
    public function disbursements(Request $request)
    {
        $query = EmployeeBonus::with(['user', 'bonusSetting', 'branch'])->orderBy('created_at', 'desc');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('bonus_month')) {
            $query->where('bonus_month', Carbon::parse($request->bonus_month . '-01')->format('Y-m-d'));
        }

        applyBranchScope($query, 'branch_id');

        $disbursements = $query->paginate(20);

        $branchesQuery = Branch::query();
        applyBranchScope($branchesQuery, 'id');
        $branches = $branchesQuery->pluck('branch_name', 'id');

        return view('bonuses.disbursements', compact('disbursements', 'branches'));
    }

    /**
     * Update disbursement payment status (e.g. approve/pay).
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $disbursement = EmployeeBonus::find($id);

        if (!$disbursement) {
            return response()->json(['success' => false, 'message' => 'Disbursement record not found.']);
        }

        checkBranchAccess($disbursement->branch_id);

        $status = $request->input('payment_status');
        if (!in_array($status, ['pending', 'approved', 'paid'])) {
            return response()->json(['success' => false, 'message' => 'Invalid payment status.']);
        }

        $disbursement->payment_status = $status;
        if ($status === 'paid') {
            $disbursement->payment_date = now()->format('Y-m-d');
        }
        $disbursement->save();

        return response()->json(['success' => true, 'message' => 'Bonus payment status updated to ' . ucfirst($status)]);
    }
}
