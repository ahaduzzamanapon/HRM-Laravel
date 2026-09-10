@php
    $authUser = auth()->user();
    $isEmpRole = \App\Services\AuthorizationEngine::isEmployeeRole($authUser);
    $canManageForm = isset($canManage) ? $canManage : (!$isEmpRole && (isSuperAdmin() || \App\Services\AuthorizationEngine::isHRRole($authUser) || can('manage_employee_children_education_supports')));
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

<!-- Child Name Field -->
<div class="col-md-3">
    <div class="form-group mb-3">
        {!! Form::label('child_name', 'Child Name:', ['class' => 'fw-semibold mb-1']) !!}
        {!! Form::text('child_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter child name']) !!}
    </div>
</div>

<!-- Exam Name Field -->
<div class="col-md-3">
    <div class="form-group mb-3">
        {!! Form::label('exam_name', 'Exam / Academic Level:', ['class' => 'fw-semibold mb-1']) !!}
        {!! Form::text('exam_name', null, ['class' => 'form-control', 'placeholder' => 'e.g. SSC, HSC, Class 10']) !!}
    </div>
</div>

<!-- GPA Field -->
<div class="col-md-3">
    <div class="form-group mb-3">
        {!! Form::label('gpa', 'GPA / Result:', ['class' => 'fw-semibold mb-1']) !!}
        {!! Form::number('gpa', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => '5.00']) !!}
    </div>
</div>

<!-- Financial Assistance Field -->
<div class="col-md-3">
    <div class="form-group mb-3">
        {!! Form::label('financial_assistance', 'Assistance Amount (৳):', ['class' => 'fw-semibold mb-1']) !!}
        {!! Form::number('financial_assistance', null, ['class' => 'form-control', 'step' => '0.01', 'required' => 'required', 'placeholder' => '0.00']) !!}
    </div>
</div>

<!-- Support Date Field -->
<div class="col-md-3">
    <div class="form-group mb-3">
        {!! Form::label('support_date', 'Support Date:', ['class' => 'fw-semibold mb-1']) !!}
        {!! Form::date('support_date', isset($employeeChildrenEducationSupport) ? $employeeChildrenEducationSupport->support_date : date('Y-m-d'), ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>

<!-- Attachment Field -->
<div class="col-md-3">
    <div class="form-group mb-3">
        {!! Form::label('attachment', 'Marksheet / Academic Certificate Attachment:', ['class' => 'fw-semibold mb-1']) !!}
        <input type="file" name="attachment" class="form-control" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx">
        @if(isset($employeeChildrenEducationSupport) && $employeeChildrenEducationSupport->attachment)
            <small class="d-block mt-1"><a href="{{ asset($employeeChildrenEducationSupport->attachment) }}" target="_blank" class="text-primary"><i class="im im-icon-File-TXT me-1"></i>View Current Document</a></small>
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
        {!! Form::label('remarks', 'Remarks / Details:', ['class' => 'fw-semibold mb-1']) !!}
        {!! Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Enter education support details...']) !!}
    </div>
</div>

<!-- Submit Field -->
<div class="col-12 mt-3">
    {!! Form::submit('Submit Application', ['class' => 'btn btn-primary rounded-pill px-4']) !!}
    <a href="{{ route('employeeChildrenEducationSupports.index') }}" class="btn btn-outline-secondary rounded-pill px-4 ms-2">Cancel</a>
</div>
