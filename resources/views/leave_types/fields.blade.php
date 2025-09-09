<div class="row">
    <div class="col-md-6">
        <!-- Name Field -->
        <div class="form-group">
            {!! Form::label('name', 'Name:') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <!-- Total Days Per Year Field -->
        <div class="form-group">
            {!! Form::label('total_days_per_year', 'Total Days Per Year:') !!}
            {!! Form::number('total_days_per_year', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- Gender Criteria Field -->
        <div class="form-group">
            {!! Form::label('gender_criteria', 'Gender Criteria:') !!}
            {!! Form::select('gender_criteria', ['All' => 'All', 'Male' => 'Male', 'Female' => 'Female'], null, ['class' => 'form-control', 'placeholder' => 'Select Gender Criteria']) !!}
        </div>
    </div>
</div>