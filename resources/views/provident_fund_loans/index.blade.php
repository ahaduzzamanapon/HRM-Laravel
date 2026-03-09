@extends('layouts.default')

@section('title')
Provident Fund Loans @parent
@stop

@section('content')
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Provident Fund Loans</h1>
        </div>
        <div class="separator-breadcrumb border-top"></div>
    </section>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="card">
            <section class="card-header">
                <h5 class="card-title d-inline">Provident Fund Loans</h5>
                <span class="float-right">
                    <a class="btn btn-primary pull-right" href="{{ route('providentFundLoans.create') }}">Add New Loan</a>
                </span>
            </section>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="providentFundLoans-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Amount</th>
                                <th>Interest Rate</th>
                                <th>Installments</th>
                                <th>Monthly Inst.</th>
                                <th>Next Payment Date</th>
                                <th>Outstanding Balance</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loans as $loan)
                                <tr>
                                    <td>{{ $loan->employee->name }} {{ $loan->employee->last_name }}
                                        ({{ $loan->employee->emp_id }})</td>
                                    <td>{{ number_format($loan->amount, 2) }}</td>
                                    <td>{{ number_format($loan->interest_rate, 2) }}%</td>
                                    <td>{{ $loan->installments }}</td>
                                    <td>{{ number_format($loan->monthly_installment, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($loan->next_payment_date)->format('d M, Y') }}</td>
                                    <td>{{ number_format($loan->outstanding_balance, 2) }}</td>
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
                                    <td>
                                        <div class='btn-group'>
                                            <a href="{{ route('providentFundLoans.show', [$loan->id]) }}"
                                                class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye"
                                                    data-placement="top" title="View"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $loans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection