<!-- Grade Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('grade', 'Grade:',['class'=>'control-label']) !!}
        {!! Form::text('grade', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Starting Salary Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('starting_salary', 'Starting Salary:',['class'=>'control-label']) !!}
        {!! Form::number('starting_salary', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- End Salary Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('end_salary', 'End Salary:',['class'=>'control-label']) !!}
        {!! Form::number('end_salary', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Description Field -->
<div class="col-md-12">
    <div class="form-group ">
        {!! Form::label('description', 'Description:',['class'=>'control-label']) !!}
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('salaryGrades.index') }}" class="btn btn-danger">Cancel</a>
</div>
