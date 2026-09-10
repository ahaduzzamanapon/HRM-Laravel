<!-- Case Progress Timeline -->
<div class="mb-5 border-bottom pb-4">
    <h5 class="fw-bold text-secondary mb-3"><i class="fa fa-sliders me-2 text-primary"></i>Case Progression Timeline</h5>
    <div class="row text-center position-relative">
        <div class="col-md-3 mb-2">
            <div class="p-3 bg-light rounded-3 border border-2 border-primary">
                <span class="badge bg-primary rounded-circle mb-2">1</span>
                <h6 class="fw-bold mb-1">Reported</h6>
                <small class="text-muted d-block">{{ $departmentalCase->incident_date ? \Carbon\Carbon::parse($departmentalCase->incident_date)->format('d M, Y') : $departmentalCase->created_at->format('d M, Y') }}</small>
            </div>
        </div>

        <div class="col-md-3 mb-2">
            <div class="p-3 bg-light rounded-3 border border-2 {{ $departmentalCase->show_cause_date ? 'border-warning' : 'border-secondary opacity-50' }}">
                <span class="badge {{ $departmentalCase->show_cause_date ? 'bg-warning text-dark' : 'bg-secondary' }} rounded-circle mb-2">2</span>
                <h6 class="fw-bold mb-1">Show Cause Notice</h6>
                <small class="text-muted d-block">{{ $departmentalCase->show_cause_date ? \Carbon\Carbon::parse($departmentalCase->show_cause_date)->format('d M, Y') : 'Pending Notice' }}</small>
            </div>
        </div>

        <div class="col-md-3 mb-2">
            <div class="p-3 bg-light rounded-3 border border-2 {{ in_array($departmentalCase->status, ['Under Investigation', 'Hearing Scheduled', 'Penalty Imposed', 'Closed']) ? 'border-info' : 'border-secondary opacity-50' }}">
                <span class="badge {{ in_array($departmentalCase->status, ['Under Investigation', 'Hearing Scheduled', 'Penalty Imposed', 'Closed']) ? 'bg-info text-dark' : 'bg-secondary' }} rounded-circle mb-2">3</span>
                <h6 class="fw-bold mb-1">Inquiry / Hearing</h6>
                <small class="text-muted d-block">{{ $departmentalCase->committee_comments ? 'Inquiry Completed' : 'Under Review' }}</small>
            </div>
        </div>

        <div class="col-md-3 mb-2">
            <div class="p-3 bg-light rounded-3 border border-2 {{ in_array($departmentalCase->status, ['Penalty Imposed', 'Closed', 'Dismissed']) ? 'border-danger' : 'border-secondary opacity-50' }}">
                <span class="badge {{ in_array($departmentalCase->status, ['Penalty Imposed', 'Closed', 'Dismissed']) ? 'bg-danger' : 'bg-secondary' }} rounded-circle mb-2">4</span>
                <h6 class="fw-bold mb-1">Final Decision</h6>
                <small class="text-muted d-block">{{ $departmentalCase->penalty ? $departmentalCase->penalty->name : ($departmentalCase->status ?? 'In Progress') }}</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Employee & Case Specs -->
    <div class="col-lg-5">
        <!-- Employee Info Card -->
        <div class="card border shadow-sm mb-4">
            <div class="card-header bg-light py-2 fw-bold text-dark">
                <i class="fa fa-user me-2 text-primary"></i>Employee Information
            </div>
            <div class="card-body">
                @if($departmentalCase->employee)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 font-weight-bold fs-4" style="width: 50px; height: 50px;">
                            {{ strtoupper(substr($departmentalCase->employee->name, 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">{{ $departmentalCase->employee->name }} {{ $departmentalCase->employee->last_name }}</h5>
                            <span class="text-muted small">Employee ID: <strong>{{ $departmentalCase->employee->emp_id ?? 'N/A' }}</strong></span>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Designation:</span>
                            <span class="fw-semibold">{{ $departmentalCase->employee->designation->desi_name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Branch:</span>
                            <span class="fw-semibold">{{ $departmentalCase->employee->branch->branch_name ?? 'Head Office' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Email:</span>
                            <span class="fw-semibold">{{ $departmentalCase->employee->email ?? 'N/A' }}</span>
                        </li>
                    </ul>
                @else
                    <p class="text-danger mb-0">Employee record no longer available.</p>
                @endif
            </div>
        </div>

        <!-- Metadata Specifications Table -->
        <div class="card border shadow-sm mb-4">
            <div class="card-header bg-light py-2 fw-bold text-dark">
                <i class="fa fa-info-circle me-2 text-primary"></i>Case Specifications
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <tbody>
                        <tr>
                            <th class="ps-3 text-muted w-40">Case Ref No:</th>
                            <td class="fw-bold">{{ $departmentalCase->case_no ?? ('DC-' . str_pad($departmentalCase->id, 4, '0', STR_PAD_LEFT)) }}</td>
                        </tr>
                        <tr>
                            <th class="ps-3 text-muted">Incident Date:</th>
                            <td>{{ $departmentalCase->incident_date ? \Carbon\Carbon::parse($departmentalCase->incident_date)->format('d M, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-3 text-muted">Allegation Type:</th>
                            <td><span class="fw-semibold">{{ $departmentalCase->allegation_type }}</span></td>
                        </tr>
                        <tr>
                            <th class="ps-3 text-muted">Allegation Category:</th>
                            <td><span class="badge bg-light text-dark border">{{ $departmentalCase->allegation_category }}</span></td>
                        </tr>
                        <tr>
                            <th class="ps-3 text-muted">Show Cause Date:</th>
                            <td>{{ $departmentalCase->show_cause_date ? \Carbon\Carbon::parse($departmentalCase->show_cause_date)->format('d M, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-3 text-muted">Attached Document:</th>
                            <td>
                                @if($departmentalCase->document)
                                    <a href="{{ asset($departmentalCase->document) }}" target="_blank" class="btn btn-xs btn-outline-info">
                                        <i class="fa fa-paperclip me-1"></i>View Attachment
                                    </a>
                                @else
                                    <span class="text-muted">No Attachment</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-3 text-muted">Employee Notification:</th>
                            <td>
                                @if($departmentalCase->notified_at)
                                    <span class="badge bg-success-subtle text-success border border-success border-opacity-25">
                                        <i class="fa fa-check-circle me-1"></i>Sent on {{ $departmentalCase->notified_at->format('d M, Y H:i') }}
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-25">
                                        <i class="fa fa-clock-o me-1"></i>Not Dispatched
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Case Findings & Decisions -->
    <div class="col-lg-7">
        <!-- Disciplinary Issue Details -->
        <div class="card border shadow-sm mb-4">
            <div class="card-header bg-light py-2 fw-bold text-dark">
                <i class="fa fa-file-text-o me-2 text-primary"></i>Disciplinary Issue Details & Allegation Summary
            </div>
            <div class="card-body">
                <p class="mb-0 text-secondary" style="white-space: pre-line;">{{ $departmentalCase->disciplinary_issue_details }}</p>
            </div>
        </div>

        <!-- Employee Show Cause Defense -->
        <div class="card border shadow-sm mb-4">
            <div class="card-header bg-light py-2 fw-bold text-dark">
                <i class="fa fa-commenting-o me-2 text-warning"></i>Employee Show Cause Response / Defense
            </div>
            <div class="card-body">
                @if($departmentalCase->show_cause_explanation)
                    <p class="mb-0 text-secondary" style="white-space: pre-line;">{{ $departmentalCase->show_cause_explanation }}</p>
                @else
                    <p class="text-muted mb-0 italic">No formal written response submitted by employee yet.</p>
                @endif
            </div>
        </div>

        <!-- Committee Findings -->
        <div class="card border shadow-sm mb-4">
            <div class="card-header bg-light py-2 fw-bold text-dark">
                <i class="fa fa-users me-2 text-info"></i>Inquiry Committee Findings & Comments
            </div>
            <div class="card-body">
                @if($departmentalCase->committee_comments)
                    <p class="mb-0 text-secondary" style="white-space: pre-line;">{{ $departmentalCase->committee_comments }}</p>
                @else
                    <p class="text-muted mb-0 italic">Committee investigation pending or comments not recorded.</p>
                @endif
            </div>
        </div>

        <!-- Penalty Imposed & Final Action -->
        <div class="card border border-2 border-danger shadow-sm mb-4">
            <div class="card-header bg-danger bg-opacity-10 py-2 fw-bold text-danger">
                <i class="fa fa-gavel me-2"></i>Sanction / Penalty Imposed & Final Action
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <span class="text-muted d-block small">Penalty Imposed:</span>
                        <h5 class="fw-bold text-danger mb-0">
                            {{ $departmentalCase->penalty ? $departmentalCase->penalty->name : 'No Penalty Assigned' }}
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted d-block small">Financial Deduction / Fine Amount:</span>
                        <h5 class="fw-bold text-dark mb-0">
                            {{ $departmentalCase->penalty_amount && $departmentalCase->penalty_amount > 0 ? number_format($departmentalCase->penalty_amount, 2) : 'N/A' }}
                        </h5>
                    </div>
                </div>

                <hr class="my-3">

                <span class="text-muted d-block small mb-1">Final Action / Executive Order:</span>
                @if($departmentalCase->final_action_taken)
                    <p class="fw-bold text-dark mb-0" style="white-space: pre-line;">{{ $departmentalCase->final_action_taken }}</p>
                @else
                    <p class="text-muted mb-0 italic">Final sanction order not finalized.</p>
                @endif
            </div>
        </div>
    </div>
</div>
