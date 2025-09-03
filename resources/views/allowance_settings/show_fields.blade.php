<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $allowanceSetting->name }}</p>
</div>

<!-- Type Field -->
<div class="col-sm-12">
    {!! Form::label('type', 'Type:') !!}
    <p>{{ $allowanceSetting->type }}</p>
</div>

<!-- Value Field -->
<div class="col-sm-12">
    {!! Form::label('value', 'Value:') !!}
    <p>{{ $allowanceSetting->value }}</p>
</div>

<!-- Tax Free Limit Field -->
<div class="col-sm-12">
    {!! Form::label('tax_free_limit', 'Tax Free Limit:') !!}
    <p>{{ $allowanceSetting->tax_free_limit }}</p>
</div>

<!-- City Specific Field -->
<div class="col-sm-12">
    {!! Form::label('city_specific', 'City Specific:') !!}
    <p>{{ $allowanceSetting->city_specific ? 'Yes' : 'No' }}</p>
</div>

<!-- City Value Field -->
<div class="col-sm-12">
    {!! Form::label('city_value', 'City Value:') !!}
    <p>{{ $allowanceSetting->city_value }}</p>
</div>

<!-- Is Active Field -->
<div class="col-sm-12">
    {!! Form::label('is_active', 'Is Active:') !!}
    <p>{{ $allowanceSetting->is_active ? 'Yes' : 'No' }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $allowanceSetting->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $allowanceSetting->updated_at }}</p>
</div>
