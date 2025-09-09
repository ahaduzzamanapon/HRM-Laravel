<!-- Employee Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('employee_id', 'Employee:') !!}
        {!! Form::select('employee_id', $users->pluck('name','id'), null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Child Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('child_name', 'Child Name:') !!}
        {!! Form::text('child_name', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Exam Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('exam_name', 'Exam Name:') !!}
        {!! Form::text('exam_name', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- GPA Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('gpa', 'GPA:') !!}
        {!! Form::number('gpa', null, ['class' => 'form-control', 'step' => '0.01'])
        !!}
    </div>
</div>

<!-- Financial Assistance Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('financial_assistance', 'Financial Assistance:') !!}
        {!! Form::number('financial_assistance', null, ['class' => 'form-control', 'step' => '0.01'])
        !!}
    </div>
</div>

<!-- Support Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('support_date', 'Support Date:') !!}
        {!! Form::text('support_date', null, ['class' => 'form-control','id'=>'support_date'])
        !!}
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $('#support_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endpush

<!-- Remarks Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('remarks', 'Remarks:') !!}
        {!! Form::textarea('remarks', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary'])
    !!}
    <a href="{{ route('employeeChildrenEducationSupports.index') }}" class="btn btn-default">Cancel</a>
</div>
