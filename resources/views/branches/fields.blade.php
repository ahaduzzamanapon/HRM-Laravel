<!-- Branch Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('branch_name', 'Branch Name:',['class'=>'control-label']) !!}
        {!! Form::text('branch_name', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Address Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('Address', 'Address:',['class'=>'control-label']) !!}
        {!! Form::text('Address', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Status Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('status', 'Status:',['class'=>'control-label']) !!}
        {!! Form::select('status', ['Active' => 'Active', 'Inactive' => 'Inactive'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Description Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('description', 'Description:',['class'=>'control-label']) !!}
        {!! Form::text('description', null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- TA Rate Per KM Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('ta_rate_per_km', 'TA Rate Per KM:',['class'=>'control-label']) !!}
        {!! Form::number('ta_rate_per_km', null, ['class' => 'form-control', 'step' => '0.01']) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('branches.index') }}" class="btn btn-danger">Cancel</a>
</div>
