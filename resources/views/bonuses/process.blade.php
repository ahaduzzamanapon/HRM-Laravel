@extends('layouts.default')

@section('title', 'Process Bonus')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Process Bonus Calculation</h3>
            <p class="text-muted mb-0">Rule: <span class="badge bg-primary fs-6">{{ $bonusSetting->title }}</span> | Month: {{ \Carbon\Carbon::parse($bonusSetting->bonus_month)->format('M Y') }}</p>
        </div>
        <a href="{{ route('bonuses.index') }}" class="btn btn-secondary">
            <i class="im im-icon-Arrow-Left me-1"></i> Back to Rules
        </a>
    </div>

    {{-- Summary Card --}}
    <div class="card mb-4 border-0 shadow-sm bg-light">
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3 border-end">
                    <small class="text-muted d-block">Calculation Base</small>
                    <strong class="fs-6 text-uppercase">{{ str_replace('_', ' ', $bonusSetting->calculation_base) }}</strong>
                </div>
                <div class="col-md-3 border-end">
                    <small class="text-muted d-block">Rate / Fixed Amount</small>
                    <strong class="fs-6 text-primary">
                        {{ $bonusSetting->bonus_type == 'percentage' ? $bonusSetting->amount_percentage . '%' : number_format($bonusSetting->amount_percentage, 2) . ' BDT' }}
                    </strong>
                </div>
                <div class="col-md-3 border-end">
                    <small class="text-muted d-block">Eligible Employees</small>
                    <strong class="fs-6 text-success">{{ $eligibleUsers->count() }} Employees</strong>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Target Branch</small>
                    <strong class="fs-6">{{ $bonusSetting->branch ? $bonusSetting->branch->branch_name : 'All Assigned Branches' }}</strong>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('bonuses.process.store', $bonusSetting->id) }}" method="POST">
        @csrf

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark"><i class="im im-icon-Checked-User me-2"></i>Eligible Employee List</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="select-all-btn">Select All</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselect-all-btn">Deselect All</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;" class="text-center">
                                    <input type="checkbox" id="master-checkbox" checked class="form-check-input">
                                </th>
                                <th>Emp ID</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Religion</th>
                                <th>Date of Join</th>
                                <th class="text-end">Base Amount (BDT)</th>
                                <th class="text-end" style="width: 200px;">Bonus Amount (BDT)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($eligibleUsers as $user)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="selected_users[]" value="{{ $user->id }}" checked class="form-check-input user-checkbox">
                                    </td>
                                    <td><code>{{ $user->emp_id ?: 'N/A' }}</code></td>
                                    <td class="fw-bold">{{ $user->name }} {{ $user->last_name }}</td>
                                    <td>{{ $user->designation->desi_name ?? 'N/A' }}</td>
                                    <td>{{ $user->department->name ?? 'N/A' }}</td>
                                    <td>{{ $user->religion ?: 'N/A' }}</td>
                                    <td>{{ $user->date_of_join ? \Carbon\Carbon::parse($user->date_of_join)->format('d M, Y') : 'N/A' }}</td>
                                    <td class="text-end font-monospace">
                                        {{ number_format($user->calculated_base_amount, 2) }}
                                        <input type="hidden" name="base_amounts[{{ $user->id }}]" value="{{ $user->calculated_base_amount }}">
                                    </td>
                                    <td class="text-end">
                                        <input type="number" step="0.01" min="0" name="bonus_amounts[{{ $user->id }}]" value="{{ $user->calculated_bonus_amount }}" class="form-control form-control-sm text-end fw-bold text-success font-monospace">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="im im-icon-Information display-6 d-block mb-2 text-secondary"></i>
                                        No eligible employees match the bonus criteria (religion, min service, or active status).
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($eligibleUsers->count() > 0)
                <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted">Total Eligible: <strong>{{ $eligibleUsers->count() }}</strong></span>
                    <button type="submit" class="btn btn-success btn-lg px-4" onclick="return confirm('Are you sure you want to generate bonus records for the selected employees?')">
                        <i class="im im-icon-Check me-1"></i> Generate & Save Bonus Disbursements
                    </button>
                </div>
            @endif
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const masterCheckbox = document.getElementById('master-checkbox');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const selectAllBtn = document.getElementById('select-all-btn');
    const deselectAllBtn = document.getElementById('deselect-all-btn');

    if (masterCheckbox) {
        masterCheckbox.addEventListener('change', function () {
            userCheckboxes.forEach(cb => cb.checked = masterCheckbox.checked);
        });
    }

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function () {
            userCheckboxes.forEach(cb => cb.checked = true);
            if (masterCheckbox) masterCheckbox.checked = true;
        });
    }

    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function () {
            userCheckboxes.forEach(cb => cb.checked = false);
            if (masterCheckbox) masterCheckbox.checked = false;
        });
    }
});
</script>
@endsection
