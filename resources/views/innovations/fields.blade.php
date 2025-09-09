<!-- Employee Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('employee_id', 'Employee:') !!}
        {!! Form::select('employee_id', $users->pluck('name','id'), null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Title Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('title', 'Title:') !!}
        {!! Form::text('title', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Innovation Type Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('innovation_type', 'Innovation Type:') !!}
        {!! Form::select('innovation_type', ['Process' => 'Process', 'Product' => 'Product', 'Service' => 'Service', 'Efficiency' => 'Efficiency'], null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Submission Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('submission_date', 'Submission Date:') !!}
        {!! Form::date('submission_date', null, ['class' => 'form-control','id'=>'submission_date'])
        !!}
    </div>
</div>



<!-- Verifier Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('verifier_id', 'Verifier:') !!}
        {!! Form::select('verifier_id', $users->pluck('name','id'), null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Verification Status Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('verification_status', 'Verification Status:') !!}
        {!! Form::select('verification_status', ['Pending' => 'Pending', 'Approved' => 'Approved', 'Rejected' => 'Rejected'], null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Verification Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('verification_date', 'Verification Date:') !!}
        {!! Form::date('verification_date', null, ['class' => 'form-control','id'=>'verification_date'])
        !!}
    </div>
</div>



<!-- Document Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('document', 'Document:') !!}
        {!! Form::file('document') !!}
    </div>
</div>

<!-- Description Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('description', 'Description:') !!}
        {!! Form::textarea('description', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

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
    <a href="{{ route('innovations.index') }}" class="btn btn-default">Cancel</a>
</div>
