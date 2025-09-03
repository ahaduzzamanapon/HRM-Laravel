<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::select('type', ['percentage' => 'Percentage', 'fixed' => 'Fixed'], null, ['class' => 'form-control']) !!}
</div>

<!-- Value Field -->
<div class="form-group col-sm-6">
    {!! Form::label('value', 'Value:') !!}
    {!! Form::number('value', null, ['class' => 'form-control', 'step' => '0.01']) !!}
</div>

<!-- Tax Free Limit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tax_free_limit', 'Tax Free Limit:') !!}
    {!! Form::number('tax_free_limit', null, ['class' => 'form-control', 'step' => '0.01']) !!}
</div>

<!-- City Specific Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('city_specific', 0) !!}
        {!! Form::checkbox('city_specific', 1, null, ['class' => 'form-check-input']) !!}
        {!! Form::label('city_specific', 'City Specific', ['class' => 'form-check-label']) !!}
    </div>
</div>

<!-- City Value Field -->
<div class="form-group col-sm-6">
    {!! Form::label('city_value', 'City Value:') !!}
    {!! Form::number('city_value', null, ['class' => 'form-control', 'step' => '0.01']) !!}
</div>

<!-- Is Active Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('is_active', 0) !!}
        {!! Form::checkbox('is_active', 1, null, ['class' => 'form-check-input']) !!}
        {!! Form::label('is_active', 'Is Active', ['class' => 'form-check-label']) !!}
    </div>
</div>
