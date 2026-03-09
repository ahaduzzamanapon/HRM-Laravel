@extends('layouts.default')

@section('title')
Record Recovery @parent
@stop

@section('content')
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>{{ __('Record') }} Repayment</h1>
        </div>
        <div class="separator-breadcrumb border-top"></div>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                {!! Form::open(['route' => 'providentFundLoanRepayments.store', 'class' => 'form-horizontal col-md-12']) !!}
                <div class="row">
                    <div class="form-group col-sm-6">
                        {!! Form::label('provident_fund_loan_id', 'Select Active Loan (Employee):') !!}
                        {!! Form::select('provident_fund_loan_id', $activeLoans, $loanId, ['class' => 'form-control select2', 'required', 'placeholder' => 'Select a Loan to Repay']) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('amount', 'Repayment Amount:') !!}
                        {!! Form::number('amount', null, ['class' => 'form-control', 'required', 'step' => '0.01', 'min' => 1]) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('repayment_date', 'Payment Date:') !!}
                        {!! Form::date('repayment_date', \Carbon\Carbon::now()->format('Y-m-d'), ['class' => 'form-control', 'id' => 'repayment_date', 'required']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('remarks', 'Notes/Remarks:') !!}
                        {!! Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => 3]) !!}
                    </div>
                </div>
                <div class="card-footer">
                    {!! Form::submit('Submit Repayment', ['class' => 'btn btn-success']) !!}
                    @if($loanId)
                        <a href="{{ route('providentFundLoans.show', $loanId) }}" class="btn btn-default">Cancel</a>
                    @else
                        <a href="{{ route('providentFundLoanRepayments.index') }}" class="btn btn-default">Cancel</a>
                    @endif
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@endpush