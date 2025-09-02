<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('training_name', 'Training Name:', ['class' => 'control-label']) !!}
            {!! Form::text('training_name', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('training_provider', 'Training Provider:', ['class' => 'control-label']) !!}
            {!! Form::text('training_provider', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('training_type', 'Training Type:', ['class' => 'control-label']) !!}
            {!! Form::select('training_type', ['Domestic' => 'Domestic', 'Foreign' => 'Foreign'], null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('start_date', 'Start Date:', ['class' => 'control-label']) !!}
            {!! Form::date('start_date', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('end_date', 'End Date:', ['class' => 'control-label']) !!}
            {!! Form::date('end_date', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('document', 'Document:', ['class' => 'control-label']) !!}
            {!! Form::file('document', ['class' => 'form-control']) !!}
        </div>
    </div>
</div>
<div class="form-group">
    {!! Form::label('description', 'Description:', ['class' => 'control-label']) !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3]) !!}
</div>