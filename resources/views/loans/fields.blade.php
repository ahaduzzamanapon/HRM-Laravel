<!-- Employee Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('employee_id', 'Employee:') !!}
        {!! Form::select('employee_id', $users->pluck('name','id'), null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Loan Type Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('loan_type_id', 'Loan Type:') !!}
        {!! Form::select('loan_type_id', $loanTypes->pluck('name','id'), null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Amount Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('amount', 'Amount:') !!}
        {!! Form::number('amount', null, ['class' => 'form-control', 'step' => '0.01'])
        !!}
    </div>
</div>

<!-- Interest Rate Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('interest_rate', 'Interest Rate:') !!}
        {!! Form::number('interest_rate', null, ['class' => 'form-control', 'step' => '0.01'])
        !!}
    </div>
</div>

<!-- Installments Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('installments', 'Installments:') !!}
        {!! Form::number('installments', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Monthly Installment Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('monthly_installment', 'Monthly Installment:') !!}
        {!! Form::number('monthly_installment', null, ['class' => 'form-control', 'step' => '0.01'])
        !!}
    </div>
</div>

<!-- Disbursement Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('disbursement_date', 'Disbursement Date:') !!}
        {!! Form::text('disbursement_date', null, ['class' => 'form-control','id'=>'disbursement_date'])
        !!}
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $('#disbursement_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endpush

<!-- Next Payment Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('next_payment_date', 'Next Payment Date:') !!}
        {!! Form::text('next_payment_date', null, ['class' => 'form-control','id'=>'next_payment_date'])
        !!}
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $('#next_payment_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endpush

<!-- Outstanding Balance Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('outstanding_balance', 'Outstanding Balance:') !!}
        {!! Form::number('outstanding_balance', null, ['class' => 'form-control', 'step' => '0.01'])
        !!}
    </div>
</div>

<!-- Status Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('status', 'Status:') !!}
        {!! Form::select('status', ['Pending' => 'Pending', 'Approved' => 'Approved', 'Disbursed' => 'Disbursed', 'Repaid' => 'Repaid', 'Rejected' => 'Rejected'], null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Remarks Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('remarks', 'Remarks:') !!}
        {!! Form::textarea('remarks', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary'])
    !!}
    <a href="{{ route('loans.index') }}" class="btn btn-default">Cancel</a>
</div>
