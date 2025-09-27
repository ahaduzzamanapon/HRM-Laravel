<!-- Bank Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('bank_name', 'Bank Name:',['class'=>'control-label']) !!}
        {!! Form::text('bank_name', null, ['class' => 'form-control']) !!}
    </div>
</div>


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
        {!! Form::label('address', 'Address:',['class'=>'control-label']) !!}
        {!! Form::text('address', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Bank Code Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('bank_code', 'Bank Code:',['class'=>'control-label']) !!}
        {!! Form::text('bank_code', null, ['class' => 'form-control']) !!}
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
    <a href="{{ route('bankSetups.index') }}" class="btn btn-danger">Cancel</a>
</div>
