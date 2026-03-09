@extends('layouts.default')

@section('title')
Provident Fund Loan @parent
@stop

@section('content')
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>{{ __('Create New') }} Provident Fund Loan</h1>
        </div>
        <div class="separator-breadcrumb border-top"></div>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="card">
            <div class="card-body">
                {!! Form::open(['route' => 'providentFundLoans.store', 'class' => 'form-horizontal col-md-12']) !!}
                <div class="row">
                    <div class="form-group col-sm-6">
                        {!! Form::label('employee_id', 'Employee:') !!}
                        {!! Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'required']) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('amount', 'Loan Amount:') !!}
                        {!! Form::number('amount', null, ['class' => 'form-control', 'required', 'min' => 1]) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('interest_rate', 'Interest Rate (%):') !!}
                        {!! Form::number('interest_rate', 0, ['class' => 'form-control', 'required', 'step' => '0.01', 'min' => 0]) !!}
                        <small class="text-muted">Enter 0 if no interest.</small>
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('installments', 'Number of Installments (Months):') !!}
                        {!! Form::number('installments', null, ['class' => 'form-control', 'required', 'min' => 1]) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('disbursement_date', 'Disbursement Date:') !!}
                        {!! Form::date('disbursement_date', \Carbon\Carbon::now()->format('Y-m-d'), ['class' => 'form-control', 'id' => 'disbursement_date', 'required']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('remarks', 'Remarks:') !!}
                        {!! Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => 3]) !!}
                    </div>

                    <div class="card bg-light mt-3 w-100">
                        <div class="card-body">
                            <h5 class="card-title">Preview Calculation</h5>
                            <p class="card-text">The monthly installment and next payment date will be automatically
                                calculated when submitting.</p>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                    <a href="{{ route('providentFundLoans.index') }}" class="btn btn-default">Cancel</a>
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