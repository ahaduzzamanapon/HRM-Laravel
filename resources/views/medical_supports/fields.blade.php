@php
    $authUser = auth()->user();
    $isEmpRole = \App\Services\AuthorizationEngine::isEmployeeRole($authUser);
    $canManageForm = isset($canManage) ? $canManage : (!$isEmpRole && (isSuperAdmin() || \App\Services\AuthorizationEngine::isHRRole($authUser) || can('manage_medical_supports')));
@endphp

<!-- Employee Id Field -->
@if($canManageForm)
    <div class="col-md-3">
        <div class="form-group mb-3">
            {!! Form::label('employee_id', 'Employee:', ['class' => 'fw-semibold mb-1']) !!}
            {!! Form::select('employee_id', $users->pluck('name','id'), null, ['class' => 'form-select']) !!}
        </div>
    </div>
@else
    <input type="hidden" name="employee_id" value="{{ auth()->id() }}">
@endif

<!-- Amount Field -->
<div class="col-md-3">
    <div class="form-group mb-3">
        {!! Form::label('amount', 'Support Amount (৳):', ['class' => 'fw-semibold mb-1']) !!}
        {!! Form::number('amount', null, ['class' => 'form-control', 'step' => '0.01', 'required' => 'required', 'placeholder' => '0.00']) !!}
    </div>
</div>

<!-- Support Date Field -->
<div class="col-md-3">
    <div class="form-group mb-3">
        {!! Form::label('support_date', 'Support Date:', ['class' => 'fw-semibold mb-1']) !!}
        {!! Form::date('support_date', isset($medicalSupport) ? $medicalSupport->support_date : date('Y-m-d'), ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>

<!-- Attachment Field -->
<div class="col-md-3">
    <div class="form-group mb-3">
        {!! Form::label('attachment', 'Document / Medical Bill Attachment:', ['class' => 'fw-semibold mb-1']) !!}
        <input type="file" name="attachment" class="form-control" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx">
        @if(isset($medicalSupport) && $medicalSupport->attachment)
            <small class="d-block mt-1"><a href="{{ asset($medicalSupport->attachment) }}" target="_blank" class="text-primary"><i class="im im-icon-File-TXT me-1"></i>View Current Document</a></small>
        @endif
    </div>
</div>

<!-- Status Field for Admin -->
@if($canManageForm)
    <div class="col-md-3">
        <div class="form-group mb-3">
            {!! Form::label('status', 'Status:', ['class' => 'fw-semibold mb-1']) !!}
            {!! Form::select('status', ['Pending' => 'Pending', 'Approved' => 'Approved', 'Disbursed' => 'Disbursed', 'Rejected' => 'Rejected'], null, ['class' => 'form-select']) !!}
        </div>
    </div>
@endif

<!-- Remarks Field -->
<div class="col-md-12">
    <div class="form-group mb-3">
        {!! Form::label('remarks', 'Remarks / Reason:', ['class' => 'fw-semibold mb-1']) !!}
        {!! Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Enter medical reason or details...']) !!}
    </div>
</div>

<!-- Submit Field -->
<div class="col-12 mt-3">
    {!! Form::submit('Submit Application', ['class' => 'btn btn-primary rounded-pill px-4']) !!}
    <a href="{{ route('medicalSupports.index') }}" class="btn btn-outline-secondary rounded-pill px-4 ms-2">Cancel</a>
</div>
