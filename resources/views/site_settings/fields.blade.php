<!-- Name Field -->
<div class="form-group">
    <div class="row">
        {!! Form::label('name', 'Name:', ['class' => 'col-md-3 col-lg-3 col-12 control-label']) !!}
        <div class="col-md-9 col-lg-9 col-12">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>


<!-- Logo Field -->
<div class="form-group">
    <div class="row">
        {!! Form::label('logo', 'Logo:', ['class' => 'col-md-3 col-lg-3 col-12 control-label']) !!}
        <div class="col-md-9 col-lg-9 col-12">{!! Form::file('logo') !!}
        </div>
    </div>
</div>
<div class="clearfix">
</div>


<!-- Slogan Field -->
<div class="form-group">
    <div class="row">
        {!! Form::label('slogan', 'Slogan:', ['class' => 'col-md-3 col-lg-3 col-12 control-label']) !!}
        <div class="col-md-9 col-lg-9 col-12">
            {!! Form::text('slogan', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('siteSettings.index') }}" class="btn btn-danger">Cancel</a>
</div>
