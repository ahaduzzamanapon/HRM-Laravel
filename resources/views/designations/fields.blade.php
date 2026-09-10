<!-- Desi Name Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('desi_name', 'Designation Name:', ['class' => 'control-label']) !!}
        {!! Form::text('desi_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter designation title']) !!}
    </div>
</div>

<!-- Desi Status Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('desi_status', 'Status:', ['class' => 'control-label']) !!}
        {!! Form::select('desi_status', ['Active' => 'Active', 'Inactive' => 'Inactive'], null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 mt-3" style="text-align-last: right;">
    {!! Form::submit('Save Designation', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('designations.index') }}" class="btn btn-secondary">Cancel</a>
</div>
