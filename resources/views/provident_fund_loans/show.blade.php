@extends('layouts.default')

@section('title')
Provident Fund Loan Summary @parent
@stop

@section('content')
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Provident Fund Loan Details</h1>
        </div>
        <div class="separator-breadcrumb border-top"></div>
    </section>
    <div class="content">
        <div class="card">
            <div class="card-header">
                <strong>Loan Information</strong>
                <a href="{{ route('providentFundLoans.index') }}" class="btn btn-default float-right">Back</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>Employee Name</th>
                                <td>{{ $loan->employee->name }} {{ $loan->employee->last_name }}</td>
                            </tr>
                            <tr>
                                <th>Employee ID</th>
                                <td>{{ $loan->employee->emp_id }}</td>
                            </tr>
                            <tr>
                                <th>Principal Amount</th>
                                <td>{{ number_format($loan->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Interest Rate</th>
                                <td>{{ number_format($loan->interest_rate, 2) }}%</td>
                            </tr>
                            <tr>
                                <th>Total Installments</th>
                                <td>{{ $loan->installments }} Months</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>Monthly Installment</th>
                                <td class="text-primary font-weight-bold">{{ number_format($loan->monthly_installment, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Disbursement Date</th>
                                <td>{{ \Carbon\Carbon::parse($loan->disbursement_date)->format('d M, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Next Payment Date</th>
                                <td class="text-danger font-weight-bold">{{ $loan->next_payment_date ? \Carbon\Carbon::parse($loan->next_payment_date)->format('d M, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Outstanding Balance</th>
                                <td class="font-weight-bold">{{ number_format($loan->outstanding_balance, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($loan->status == 'Pending')
                                        <span class="badge bg-warning">{{ $loan->status }}</span>
                                    @elseif($loan->status == 'Approved' || $loan->status == 'Disbursed')
                                        <span class="badge bg-primary">{{ $loan->status }}</span>
                                    @elseif($loan->status == 'Repaid')
                                        <span class="badge bg-success">{{ $loan->status }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $loan->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <strong>Repayment History</strong>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount Paid</th>
                        <th>Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($loan->repayments as $repayment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($repayment->repayment_date)->format('d M, Y') }}</td>
                            <td>{{ number_format($repayment->amount, 2) }}</td>
                            <td>{{ $repayment->remarks }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No repayments recorded yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
