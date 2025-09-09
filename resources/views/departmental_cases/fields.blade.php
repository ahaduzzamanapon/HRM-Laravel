<!-- Employee Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('employee_id', 'Employee:') !!}
        {!! Form::select('employee_id', $users->pluck('name','id'), null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Allegation Type Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('allegation_type', 'Allegation Type:') !!}
        {!! Form::text('allegation_type', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Allegation Category Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('allegation_category', 'Allegation Category:') !!}
        {!! Form::text('allegation_category', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Penalty Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('penalty_id', 'Penalty:') !!}
        {!! Form::select('penalty_id', $penalties->pluck('name','id'), null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Disciplinary Issue Details Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('disciplinary_issue_details', 'Disciplinary Issue Details:') !!}
        {!! Form::textarea('disciplinary_issue_details', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Committee Comments Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('committee_comments', 'Committee Comments:') !!}
        {!! Form::textarea('committee_comments', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Final Action Taken Field -->
<div class="col-md-12">
    <div class="form-group">
        {!! Form::label('final_action_taken', 'Final Action Taken:') !!}
        {!! Form::textarea('final_action_taken', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary'])
    !!}
    <a href="{{ route('departmentalCases.index') }}" class="btn btn-default">Cancel</a>
</div>
