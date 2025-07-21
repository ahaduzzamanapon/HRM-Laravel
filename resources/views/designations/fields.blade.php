<!-- Desi Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('desi_name', 'Name:',['class'=>'control-label']) !!}
        {!! Form::text('desi_name', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Desi Status Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('desi_status', 'Status:',['class'=>'control-label']) !!}
        {!! Form::select('desi_status', ['Active' => 'Active', 'Inactive' => 'Inactive'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('designations.index') }}" class="btn btn-danger">Cancel</a>
</div>
