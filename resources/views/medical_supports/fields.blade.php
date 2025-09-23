<!-- Employee Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('employee_id', 'Employee:') !!}
        {!! Form::select('employee_id', $users->pluck('name','id'), null, ['class' => 'form-control'])
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

<!-- Support Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('support_date', 'Support Date:') !!}
        {!! Form::date('support_date', null, ['class' => 'form-control','id'=>'support_date'])
        !!}
    </div>
</div>

 

<!-- Remarks Field -->
<div class="col-md-12">
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
    <a href="{{ route('medicalSupports.index') }}" class="btn btn-default">Cancel</a>
</div>
