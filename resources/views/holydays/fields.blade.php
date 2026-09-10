<!-- Title Field -->
<div class="col-md-4">
    <div class="form-group">
        {!! Form::label('title', 'Title:',['class'=>'control-label']) !!}
        {!! Form::text('title', null, ['class' => 'form-control', 'required']) !!}
    </div>
</div>


<!-- Status Field -->
<div class="col-md-2">
    <div class="form-group">
        {!! Form::label('status', 'Status:',['class'=>'control-label']) !!}
        {!! Form::select('status', ['Published' => 'Published', 'Unpublished' => 'Unpublished'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- From Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('date', 'From Date:',['class'=>'control-label']) !!}
        {!! Form::date('date', isset($holyday) && $holyday->date ? \Carbon\Carbon::parse($holyday->date)->format('Y-m-d') : null, ['class' => 'form-control', 'id' => 'date', 'required']) !!}
    </div>
</div>

<!-- To Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('end_date', 'To Date (Optional):',['class'=>'control-label']) !!}
        {!! Form::date('end_date', isset($holyday) && $holyday->end_date ? \Carbon\Carbon::parse($holyday->end_date)->format('Y-m-d') : null, ['class' => 'form-control', 'id' => 'end_date']) !!}
    </div>
</div>


<!-- Descreption Field -->
<div class="col-md-12">
    <div class="form-group ">
        {!! Form::label('descreption', 'Descreption:',['class'=>'control-label']) !!}
        {!! Form::textarea('descreption', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('holydays.index') }}" class="btn btn-danger">Cancel</a>
</div>
