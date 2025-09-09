<!-- Branch Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('branch_id', 'Branch:',['class'=>'control-label']) !!}
        @php
            $branches = \App\Models\Branch::all()->pluck('branch_name','id')->prepend('Select Branch', '')->toArray();
        @endphp
        {!! Form::select('branch_id', $branches, null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Title Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('title', 'Title:',['class'=>'control-label']) !!}
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Status Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('status', 'Status:',['class'=>'control-label']) !!}
        {!! Form::select('status', ['Published' => 'Published', 'Unpublished' => 'Unpublished'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('date', 'Date:',['class'=>'control-label']) !!}
        {!! Form::date('date', null, ['class' => 'form-control','id'=>'date']) !!}
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
