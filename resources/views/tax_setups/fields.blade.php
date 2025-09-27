<!-- Title Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('title', 'Title:',['class'=>'control-label']) !!}
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Min Salary Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('min_salary', 'Min Salary:',['class'=>'control-label']) !!}
        {!! Form::number('min_salary', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Max Salary Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('max_salary', 'Max Salary:',['class'=>'control-label']) !!}
        {!! Form::number('max_salary', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Tax Yearly Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('tax_yearly', 'Tax Yearly:',['class'=>'control-label']) !!}
        {!! Form::number('tax_yearly', null, ['class' => 'form-control', 'id' => 'tax_yearly']) !!}
    </div>
</div>


<!-- Tax Monthly Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('tax_monthly', 'Tax Monthly:',['class'=>'control-label']) !!}
        {!! Form::number('tax_monthly', null, ['class' => 'form-control', 'id' => 'tax_monthly']) !!}
    </div>
</div>


<!-- Update By Field -->
        {!! Form::hidden('update_by', auth()->id(), ['class' => 'form-control']) !!}


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('taxSetups.index') }}" class="btn btn-danger">Cancel</a>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const taxYearlyInput = document.getElementById('tax_yearly');
        const taxMonthlyInput = document.getElementById('tax_monthly');

        function calculateTaxMonthly() {
            const taxYearly = parseFloat(taxYearlyInput.value) || 0;
            const taxMonthly = taxYearly / 12;
            taxMonthlyInput.value = taxMonthly.toFixed(2);
        }

        taxYearlyInput.addEventListener('input', calculateTaxMonthly);
    });
</script>
@endpush
