<!-- Titel Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('titel', 'Titel:',['class'=>'control-label']) !!}
        {!! Form::text('titel', null, ['class' => 'form-control']) !!}
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
        {!! Form::number('tax_yearly', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Tax Monthly Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('tax_monthly', 'Tax Monthly:',['class'=>'control-label']) !!}
        {!! Form::number('tax_monthly', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Update By Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('update_by', 'Update By:',['class'=>'control-label']) !!}
        {!! Form::text('update_by', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('taxSetups.index') }}" class="btn btn-danger">Cancel</a>
</div>
