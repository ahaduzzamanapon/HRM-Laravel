<!-- Employee Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('employee_id', 'Employee:') !!}
        {!! Form::select('employee_id', $users->pluck('name','id'), null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Amount Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('amount', 'Amount:') !!}
        {!! Form::number('amount', null, ['class' => 'form-control', 'step' => '0.01'])
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
    <a href="{{ route('funeralSupports.index') }}" class="btn btn-default">Cancel</a>
</div>
