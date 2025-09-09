<!-- Title Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('title', 'Title:',['class'=>'control-label']) !!}
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- User Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('user_id', 'User:') !!}
        {!! Form::select('user_id', $users->pluck('name','id'), null, ['class' => 'form-control'])
        !!}
    </div>
</div>


<!-- Document Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('document', 'Document:',['class'=>'control-label']) !!}
        {!! Form::file('document') !!}
    </div>
</div>


<!-- Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('date', 'Date:',['class'=>'control-label']) !!}
        {!! Form::date('date', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Reason Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('reason', 'Reason:',['class'=>'control-label']) !!}
        {!! Form::text('reason', null, ['class' => 'form-control']) !!}
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
    <a href="{{ route('rewardings.index') }}" class="btn btn-danger">Cancel</a>
</div>
