@extends('layouts.default')

@section('title')
PF Loan Repayments @parent
@stop

@section('content')
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Provident Fund Loan Repayments</h1>
        </div>
        <div class="separator-breadcrumb border-top"></div>
    </section>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="card">
            <section class="card-header">
                <h5 class="card-title d-inline">Provident Fund Loan Repayments</h5>
                <span class="float-right">
                    <a class="btn btn-primary pull-right" href="{{ route('providentFundLoanRepayments.create') }}">Add New
                        Repayment</a>
                </span>
            </section>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="providentFundLoanRepayments-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Employee</th>
                                <th>Amount Paid</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($repayments as $repayment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($repayment->repayment_date)->format('d M, Y') }}</td>
                                    <td>{{ $repayment->loan->employee->name }} {{ $repayment->loan->employee->last_name }}
                                        ({{ $repayment->loan->employee->emp_id }})</td>
                                    <td>{{ number_format($repayment->amount, 2) }}</td>
                                    <td>{{ $repayment->remarks }}</td>
                                    <td>
                                        <div class='btn-group'>
                                            <a href="{{ route('providentFundLoans.show', [$repayment->loan->id]) }}"
                                                class='btn btn-outline-primary btn-xs' title="View Loan"><i
                                                    class="im im-icon-Eye"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $repayments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection