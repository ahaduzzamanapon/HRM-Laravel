<!-- Section 1: Case & Employee Details -->
<div class="card border shadow-sm mb-4">
    <div class="card-header bg-light fw-bold text-primary py-2">
        <i class="fa fa-user me-2"></i>1. Case & Employee Information
    </div>
    <div class="card-body">
        <div class="row g-3">
            <!-- Employee Field -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    {!! Form::label('employee_id', 'Select Employee:', ['class' => 'form-label fw-bold']) !!} <span class="text-danger">*</span>
                    <select name="employee_id" id="employee_id" class="form-select select2" required>
                        <option value="">-- Choose Employee --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (isset($departmentalCase) && $departmentalCase->employee_id == $user->id) ? 'selected' : '' }}>
                                {{ $user->name }} {{ $user->last_name }} (ID: {{ $user->emp_id ?? 'N/A' }} | {{ $user->branch->branch_name ?? 'Head Office' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Case Ref No -->
            <div class="col-md-3">
                <div class="form-group mb-3">
                    {!! Form::label('case_no', 'Case Reference No:', ['class' => 'form-label fw-bold']) !!}
                    {!! Form::text('case_no', isset($departmentalCase) ? $departmentalCase->case_no : null, ['class' => 'form-control bg-light', 'placeholder' => 'Auto Generated (e.g. DC-2026-0001)', 'readonly' => isset($departmentalCase)]) !!}
                </div>
            </div>

            <!-- Incident Date -->
            <div class="col-md-3">
                <div class="form-group mb-3">
                    {!! Form::label('incident_date', 'Incident Date:', ['class' => 'form-label fw-bold']) !!}
                    {!! Form::date('incident_date', isset($departmentalCase) && $departmentalCase->incident_date ? $departmentalCase->incident_date->format('Y-m-d') : date('Y-m-d'), ['class' => 'form-control']) !!}
                </div>
            </div>

            <!-- Allegation Type -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    {!! Form::label('allegation_type', 'Allegation Type:', ['class' => 'form-label fw-bold']) !!} <span class="text-danger">*</span>
                    {!! Form::text('allegation_type', null, ['class' => 'form-control', 'placeholder' => 'e.g., Misconduct, Unauthorized Absence, Fraud', 'required']) !!}
                </div>
            </div>

            <!-- Allegation Category -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    {!! Form::label('allegation_category', 'Allegation Category:', ['class' => 'form-label fw-bold']) !!} <span class="text-danger">*</span>
                    {!! Form::text('allegation_category', null, ['class' => 'form-control', 'placeholder' => 'e.g., Major Violation, Policy Breach', 'required']) !!}
                </div>
            </div>

            <!-- Case Status -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    {!! Form::label('status', 'Case Status:', ['class' => 'form-label fw-bold']) !!} <span class="text-danger">*</span>
                    {!! Form::select('status', [
                        'Pending' => 'Pending',
                        'Under Investigation' => 'Under Investigation',
                        'Show Cause Issued' => 'Show Cause Issued',
                        'Hearing Scheduled' => 'Hearing Scheduled',
                        'Penalty Imposed' => 'Penalty Imposed',
                        'Dismissed' => 'Dismissed',
                        'Closed' => 'Closed'
                    ], null, ['class' => 'form-select', 'required']) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 2: Investigation & Show Cause Notice -->
<div class="card border shadow-sm mb-4">
    <div class="card-header bg-light fw-bold text-primary py-2">
        <i class="fa fa-file-text-o me-2"></i>2. Allegation Details & Show Cause Notice
    </div>
    <div class="card-body">
        <div class="row g-3">
            <!-- Disciplinary Issue Details -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    {!! Form::label('disciplinary_issue_details', 'Disciplinary Issue Details:', ['class' => 'form-label fw-bold']) !!} <span class="text-danger">*</span>
                    {!! Form::textarea('disciplinary_issue_details', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => 'Detailed description of the alleged incident, violation, or misconduct...', 'required']) !!}
                </div>
            </div>

            <!-- Show Cause Date & Explanation -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    {!! Form::label('show_cause_date', 'Show Cause Notice Date:', ['class' => 'form-label fw-bold']) !!}
                    {!! Form::date('show_cause_date', isset($departmentalCase) && $departmentalCase->show_cause_date ? $departmentalCase->show_cause_date->format('Y-m-d') : null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('show_cause_explanation', 'Employee Defense / Show Cause Explanation:', ['class' => 'form-label fw-bold']) !!}
                    {!! Form::textarea('show_cause_explanation', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => 'Summary of response submitted by the employee (if applicable)...']) !!}
                </div>
            </div>

            <!-- Attachment Document -->
            <div class="col-md-12">
                <div class="form-group mb-2">
                    {!! Form::label('document', 'Attach Supporting Document / Show Cause Copy (PDF, Doc, Image):', ['class' => 'form-label fw-bold']) !!}
                    {!! Form::file('document', ['class' => 'form-control']) !!}
                    @if(isset($departmentalCase) && $departmentalCase->document)
                        <div class="mt-2">
                            <span class="small text-muted">Current File:</span>
                            <a href="{{ asset($departmentalCase->document) }}" target="_blank" class="btn btn-xs btn-outline-info ms-1">
                                <i class="fa fa-paperclip me-1"></i>View Attached Document
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 3: Committee Findings & Penalty Decision -->
<div class="card border shadow-sm mb-4">
    <div class="card-header bg-light fw-bold text-primary py-2">
        <i class="fa fa-gavel me-2"></i>3. Committee Findings & Penalty Decision
    </div>
    <div class="card-body">
        <div class="row g-3">
            <!-- Committee Comments -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    {!! Form::label('committee_comments', 'Committee / Investigation Findings:', ['class' => 'form-label fw-bold']) !!}
                    {!! Form::textarea('committee_comments', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => 'Official comments and recommendations from the Inquiry Committee...']) !!}
                </div>
            </div>

            <!-- Final Action Taken -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    {!! Form::label('final_action_taken', 'Final Action / Sanction Decision:', ['class' => 'form-label fw-bold']) !!}
                    {!! Form::textarea('final_action_taken', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => 'Summary of final decision, reprimand, suspension, termination or closure...']) !!}
                </div>
            </div>

            <!-- Penalty Select -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    {!! Form::label('penalty_id', 'Imposed Penalty Category:', ['class' => 'form-label fw-bold']) !!}
                    <select name="penalty_id" id="penalty_id" class="form-select">
                        <option value="">-- No Penalty / Under Review --</option>
                        @foreach($penalties as $penalty)
                            <option value="{{ $penalty->id }}" {{ (isset($departmentalCase) && $departmentalCase->penalty_id == $penalty->id) ? 'selected' : '' }}>
                                {{ $penalty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Penalty Amount -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    {!! Form::label('penalty_amount', 'Financial Deduction / Penalty Amount (If Applicable):', ['class' => 'form-label fw-bold']) !!}
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-money"></i></span>
                        {!! Form::number('penalty_amount', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'placeholder' => '0.00']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 4: Employee Notification & Submit -->
<div class="card border shadow-sm mb-4 border-start border-4 border-warning">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="notify_employee" value="1" id="notifyEmployeeSwitch" {{ !isset($departmentalCase) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold text-dark ms-2" for="notifyEmployeeSwitch">
                    <i class="fa fa-envelope-o me-1 text-warning"></i> Send Official Disciplinary Notification Email to Employee
                </label>
                <small class="d-block text-muted ms-2 mt-1">Dispatches email alert detailing allegation, show-cause deadline, and penalty decisions to the employee.</small>
            </div>
        </div>
    </div>
</div>

<!-- Form Action Buttons -->
<div class="d-flex justify-content-end gap-2 mb-4">
    <a href="{{ route('departmentalCases.index') }}" class="btn btn-secondary px-4">
        <i class="fa fa-times me-1"></i> Cancel
    </a>
    <button type="submit" class="btn btn-primary px-4 shadow-sm">
        <i class="fa fa-check me-1"></i> Save Disciplinary Case
    </button>
</div>
