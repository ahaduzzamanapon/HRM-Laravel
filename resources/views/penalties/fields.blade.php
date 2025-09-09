<!-- Name Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('name', 'Name:') !!}
        {!! Form::text('name', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Type Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('type', 'Type:') !!}
        {!! Form::select('type', ['Minor' => 'Minor', 'Major' => 'Major'], null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Description Field -->
<div class="col-md-12">
    <div class="form-group">
        {!! Form::label('description', 'Description:') !!}
        {!! Form::textarea('description', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary'])
    !!}
    <a href="{{ route('penalties.index') }}" class="btn btn-default">Cancel</a>
</div>
