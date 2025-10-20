<!-- Employee Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('employee_id', 'Employee:') !!}
        @if(Auth::user()->role->name == 'Admin')
            {!! Form::select('employee_id', $users->pluck('name','id'), null, ['class' => 'form-control'])
            !!}
        @else
            {!! Form::select('employee_id', $users->pluck('name','id'), Auth::id(), ['class' => 'form-control', 'readonly'])
            !!}
        @endif
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
        {!! Form::number('monthly_installment', null, ['class' => 'form-control', 'step' => '0.01', 'id' => 'monthly_installment'])
        !!}
    </div>
</div>

@if(Auth::user()->role->name == 'Admin')
<!-- Disbursement Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('disbursement_date', 'Disbursement Date:') !!}
        {!! Form::date('disbursement_date', null, ['class' => 'form-control','id'=>'disbursement_date'])
        !!}
    </div>
</div>


<!-- Next Payment Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('next_payment_date', 'Next Payment Date:') !!}
        {!! Form::date('next_payment_date', null, ['class' => 'form-control','id'=>'next_payment_date'])
        !!}
    </div>
</div>


<!-- Outstanding Balance Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('outstanding_balance', 'Outstanding Balance:') !!}
        {!! Form::number('outstanding_balance', null, ['class' => 'form-control', 'step' => '0.01', 'readonly'])
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
@endif

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary'])
    !!}
    <a href="{{ route('loans.index') }}" class="btn btn-default">Cancel</a>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const amountInput = document.getElementById('amount');
        const interestRateInput = document.getElementById('interest_rate');
        const installmentsInput = document.getElementById('installments');
        const monthlyInstallmentInput = document.getElementById('monthly_installment');
        const outstandingBalanceInput = document.getElementById('outstanding_balance');

        console.log(outstandingBalanceInput.value);
        function calculateMonthlyInstallment() {

            const P = parseFloat(amountInput.value) || 0;              // principal
            const annualRate = parseFloat(interestRateInput.value) || 0;
            const i = (annualRate / 100) / 12;                         // monthly interest rate
            const n = parseInt(installmentsInput.value) || 0;           // total months

            if (P > 0 && annualRate > 0 && n > 0) {
                // Compound interest formula for EMI
                const M = P * (i * Math.pow(1 + i, n)) / (Math.pow(1 + i, n) - 1);
                monthlyInstallmentInput.value = M.toFixed(2);

                // Calculate total and outstanding balance (for info)
                const totalPayable = M * n;
                outstandingBalanceInput.value = totalPayable.toFixed(2);
            } else {
                monthlyInstallmentInput.value = '';
                outstandingBalanceInput.value = '';
            }
        }

        amountInput.addEventListener('input', calculateMonthlyInstallment);
        interestRateInput.addEventListener('input', calculateMonthlyInstallment);
        installmentsInput.addEventListener('input', calculateMonthlyInstallment);
    });
</script>
@endpush
