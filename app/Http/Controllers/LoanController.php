<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\User;
use App\Models\LoanType;
use Flash;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index()
    {
        $isAdmin = isSuperAdmin() || can('manage_leave_types') || can('loans');

        if ($isAdmin) {
            $query = Loan::with(['employee', 'loanType']);
            applyUserBranchScope($query, 'employee');
            $loans = $query->paginate(10);
        } else {
            $loans = Loan::with(['employee', 'loanType'])
                ->where('employee_id', Auth::id())
                ->paginate(10);
        }

        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $isAdmin = isSuperAdmin() || can('loans');

        if ($isAdmin) {
            $empQuery = User::where('group_id', '!=', 1)->where('status', 'active');
            applyBranchScope($empQuery, 'branch_id');
            $users = $empQuery->get();
        } else {
            $users = User::where('id', Auth::id())->get();
        }

        $loanTypes = LoanType::all();
        return view('loans.create', compact('users', 'loanTypes'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        Loan::create($input);
        Flash::success('Loan saved successfully.');
        return redirect(route('loans.index'));
    }

    public function show($id)
    {
        $loan = Loan::with(['employee', 'loanType', 'loanRepayments'])->find($id);
        if (empty($loan)) {
            Flash::error('Loan not found');
            return redirect(route('loans.index'));
        }

        enforceBranchOwnership($loan->employee ?? null, 'branch_id');

        $loan->outstanding_balance = $loan->amount - $loan->loanRepayments->sum('amount');
        return view('loans.show')->with('loan', $loan);
    }

    public function edit($id)
    {
        $loan = Loan::with(['employee', 'loanRepayments'])->find($id);
        if (empty($loan)) {
            Flash::error('Loan not found');
            return redirect(route('loans.index'));
        }

        enforceBranchOwnership($loan->employee ?? null, 'branch_id');

        $amount     = $loan->amount;
        $annualRate = $loan->interest_rate;
        $months     = $loan->installments;
        $r   = ($annualRate / 100) / 12;
        $emi = $months > 0 && $r > 0
            ? $amount * ($r * pow(1 + $r, $months)) / (pow(1 + $r, $months) - 1)
            : ($months > 0 ? $amount / $months : 0);
        $total = round($emi * $months, 2);

        $loan->outstanding_balance = $total - $loan->loanRepayments->sum('amount');

        $empQuery = User::where('group_id', '!=', 1);
        applyBranchScope($empQuery, 'branch_id');
        $users     = $empQuery->get();
        $loanTypes = LoanType::all();

        return view('loans.edit', compact('loan', 'users', 'loanTypes'));
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::with('employee')->find($id);
        if (empty($loan)) {
            Flash::error('Loan not found');
            return redirect(route('loans.index'));
        }

        enforceBranchOwnership($loan->employee ?? null, 'branch_id');

        $loan->fill($request->all());
        $loan->save();
        Flash::success('Loan updated successfully.');
        return redirect(route('loans.index'));
    }

    public function destroy($id)
    {
        $loan = Loan::with('employee')->find($id);
        if (empty($loan)) {
            Flash::error('Loan not found');
            return redirect(route('loans.index'));
        }

        enforceBranchOwnership($loan->employee ?? null, 'branch_id');

        $loan->delete();
        Flash::success('Loan deleted successfully.');
        return redirect(route('loans.index'));
    }
}
