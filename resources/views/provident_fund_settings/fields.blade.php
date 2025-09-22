<!-- Employee Contribution Field -->
<div class="form-group col-sm-6">
    {!! Form::label('employee_contribution', 'Employee Contribution (%):') !!}
    {!! Form::number('employee_contribution', null, ['class' => 'form-control', 'step' => '0.01'])
    !!}
</div>

<!-- Employer Contribution Field -->
<div class="form-group col-sm-6">
    {!! Form::label('employer_contribution', 'Employer Contribution (%):') !!}
    {!! Form::number('employer_contribution', null, ['class' => 'form-control', 'step' => '0.01'])
    !!}
</div>

<!-- Interest Rate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('interest_rate', 'Interest Rate (%):') !!}
    {!! Form::number('interest_rate', null, ['class' => 'form-control', 'step' => '0.01'])
    !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary'])
    !!}
</div>
