<!-- Loan Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('loan_id', 'Loan Id:') !!}
        {!! Form::select('loan_id', $loans->pluck('id','id'), null, ['class' => 'form-control'])
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

<!-- Repayment Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('repayment_date', 'Repayment Date:') !!}
        {!! Form::text('repayment_date', null, ['class' => 'form-control','id'=>'repayment_date'])
        !!}
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $('#repayment_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endpush

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
    <a href="{{ route('loanRepayments.index') }}" class="btn btn-default">Cancel</a>
</div>
