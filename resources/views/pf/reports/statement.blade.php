@extends('layouts.default')

@section('title', 'PF Statement')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h4 class="page-title m-0">Provident Fund Statement</h4>

            @if(!($isEmployee ?? false) && isset($employees) && count($employees) > 0)
                <form action="{{ route('pf.reports.statement') }}" method="GET" class="d-flex align-items-center gap-2">
                    <label class="fw-bold mb-0 text-nowrap">Select Employee:</label>
                    <select name="employee_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ ($employee->id ?? null) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }} {{ $emp->last_name }} ({{ $emp->emp_id ?? 'ID: ' . $emp->id }})
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h3 class="fw-bold text-primary">{{ config('app.name', 'HRM System') }}</h3>
                <p class="text-muted mb-1">Official Provident Fund Member Statement</p>
                <h5 class="fw-bold">PF Statement for {{ $employee->name ?? 'Employee' }} {{ $employee->last_name ?? '' }}</h5>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-bordered table-sm align-middle">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 35%">Employee Name:</th>
                            <td class="fw-bold">{{ $employee->name ?? 'N/A' }} {{ $employee->last_name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Employee ID:</th>
                            <td>{{ $employee->emp_id ?? ('EMP-' . $employee->id) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Department:</th>
                            <td>{{ $employee->department->name ?? 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered table-sm align-middle">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 35%">PF Account No:</th>
                            <td class="fw-bold text-primary">{{ $employee->pf_account_number ?? 'PF-' . strtoupper(substr(md5($employee->id), 0, 8)) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Date of Joining:</th>
                            <td>{{ $employee->date_of_join ? \Carbon\Carbon::parse($employee->date_of_join)->format('d M, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Status:</th>
                            <td>
                                <span class="badge {{ strtolower($employee->status ?? '') == 'inactive' ? 'bg-danger' : 'bg-success' }}">
                                    {{ ucfirst($employee->status ?? 'Active') }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @php
            $runningBalance = 0;
            $totalEmployeeContrib = 0;
            $totalEmployerContrib = 0;
            $totalVoluntaryContrib = 0;
            $totalProfit = 0;
        @endphp

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date / Period</th>
                                <th>Employee Contrib (৳)</th>
                                <th>Company Contrib (৳)</th>
                                <th>Voluntary / Profit (৳)</th>
                                <th>Running Balance (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contributions as $idx => $c)
                                @php
                                    $empC = (float)($c->employee_contribution ?? 0);
                                    $empR = (float)($c->employer_contribution ?? 0);
                                    $volC = (float)($c->voluntary_contribution ?? 0);
                                    $prof = (float)($c->profit_amount ?? 0);

                                    $totalEmployeeContrib += $empC;
                                    $totalEmployerContrib += $empR;
                                    $totalVoluntaryContrib += $volC;
                                    $totalProfit += $prof;

                                    $monthTotal = $empC + $empR + $volC + $prof;
                                    $runningBalance += $monthTotal;
                                @endphp
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $c->contribution_date ? \Carbon\Carbon::parse($c->contribution_date)->format('M Y') : $c->created_at->format('M Y') }}</td>
                                    <td>৳ {{ number_format($empC, 2) }}</td>
                                    <td>৳ {{ number_format($empR, 2) }}</td>
                                    <td>৳ {{ number_format($volC + $prof, 2) }}</td>
                                    <td class="fw-bold text-success">৳ {{ number_format($runningBalance, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-muted">No Provident Fund contributions found for this employee.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($contributions) > 0)
                        <tfoot>
                            <tr class="fw-bold table-light">
                                <td colspan="2">Total Accumulated</td>
                                <td>৳ {{ number_format($totalEmployeeContrib, 2) }}</td>
                                <td>৳ {{ number_format($totalEmployerContrib, 2) }}</td>
                                <td>৳ {{ number_format($totalVoluntaryContrib + $totalProfit, 2) }}</td>
                                <td class="text-success fs-6">৳ {{ number_format($runningBalance, 2) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 offset-md-6">
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <th class="bg-light w-50">Total Employee Contributions:</th>
                            <td class="text-end text-success">+ ৳ {{ number_format($totalEmployeeContrib, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light w-50">Total Employer Contributions:</th>
                            <td class="text-end text-success">+ ৳ {{ number_format($totalEmployerContrib, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light w-50">Voluntary & Profit Amount:</th>
                            <td class="text-end text-success">+ ৳ {{ number_format($totalVoluntaryContrib + $totalProfit, 2) }}</td>
                        </tr>
                        <tr class="fw-bold fs-6">
                            <th class="bg-light w-50">Total Closing Balance:</th>
                            <td class="text-end text-primary">৳ {{ number_format($runningBalance, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="row mt-3 text-center d-print-none">
            <div class="col-12">
                <button class="btn btn-primary" onclick="window.print()"><i class="fa fa-print me-1"></i> Print Statement</button>
            </div>
        </div>
    </div>
</div>
@endsection
