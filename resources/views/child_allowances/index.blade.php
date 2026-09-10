@extends('layouts.default')

@section('title', 'Child Allowances')

@section('content')
<style>
    .ca-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 12px;
        padding: 20px 24px;
        color: #ffffff;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(30,60,114,0.15);
    }
    .ca-header h2 {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 4px;
        color: #ffffff !important;
    }
    .ca-header p {
        margin: 0;
        opacity: 0.88;
        font-size: 0.95rem;
    }
    .stat-card {
        background: #ffffff;
        border-radius: 10px;
        padding: 16px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        border: 1px solid #edf2f7;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-right: 16px;
        flex-shrink: 0;
    }
    .stat-val {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }
    .stat-lbl {
        font-size: 0.82rem;
        color: #718096;
        margin-top: 2px;
    }
    .card-custom {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 24px;
    }
    .card-custom-header {
        padding: 16px 20px;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .card-custom-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        margin: 0;
    }
    .status-pill {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-active {
        background: #e6fffa;
        color: #234e52;
        border: 1px solid #b2f5ea;
    }
    .status-expired {
        background: #fff5f5;
        color: #742a2a;
        border: 1px solid #fed7d7;
    }
</style>

<div class="container-fluid">
    {{-- Header Banner --}}
    <div class="ca-header d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="im im-icon-User"></i> Child Allowance Details</h2>
            <p>{{ $isEmployeeRole ? 'View your child allowance details and recurring monthly entitlement.' : 'View and manage employee child allowance configurations and recurring payments.' }}</p>
        </div>
        @if(!$isEmployeeRole)
        <div>
            <button type="button" class="btn btn-light btn-sm" id="btn-add-allowance">
                <i class="im im-icon-Add"></i> Add New Allowance
            </button>
        </div>
        @endif
    </div>

    {{-- Summary Stat Cards --}}
    <div class="row">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: #ebf8ff; color: #3182ce;">
                    <i class="im im-icon-Face-Style"></i>
                </div>
                <div>
                    <div class="stat-val">{{ number_format($totalChildren) }}</div>
                    <div class="stat-lbl">Children Registered</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e6fffa; color: #319795;">
                    <i class="im im-icon-Money-Bag"></i>
                </div>
                <div>
                    <div class="stat-val">{{ number_format($totalAmount, 2) }}</div>
                    <div class="stat-lbl">Total Monthly Allowance</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: #faf5ff; color: #805ad5;">
                    <i class="im im-icon-Calendar-4"></i>
                </div>
                <div>
                    <div class="stat-val">{{ number_format($childAllowances->total()) }}</div>
                    <div class="stat-lbl">Total Allowance Records</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Search (Visible to Admin/HR) --}}
    @if(!$isEmployeeRole)
    <div class="card-custom">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('childAllowances.index') }}" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <select name="user_id" class="form-select select2">
                        <option value="">-- All Employees --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ (string)$selectedUserId === (string)$u->id ? 'selected' : '' }}>
                                {{ $u->name }} {{ $u->last_name }} ({{ $u->emp_id ?? 'No ID' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by child or employee name...">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="im im-icon-Filter"></i> Filter</button>
                    <a href="{{ route('childAllowances.index') }}" class="btn btn-secondary btn-sm"><i class="im im-icon-Refresh"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Allowance Records Table --}}
    <div class="card-custom">
        <div class="card-custom-header">
            <h5 class="card-custom-title"><i class="im im-icon-ID-Card"></i> Allowance Schedule List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f7fafc;">
                        <tr>
                            <th style="width: 50px;">#</th>
                            @if(!$isEmployeeRole)
                                <th>Employee</th>
                            @endif
                            <th>Child Name</th>
                            <th>Date of Birth</th>
                            <th>Start Age</th>
                            <th>Start Month</th>
                            <th>Pay Duration</th>
                            <th>End Month</th>
                            <th>Monthly Amount</th>
                            <th>Status</th>
                            @if(!$isEmployeeRole)
                                <th class="text-end" style="width: 120px;">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($childAllowances as $index => $item)
                            @php
                                $startM = \Carbon\Carbon::parse($item->start_month);
                                $endM   = \Carbon\Carbon::parse($item->end_month);
                                $now    = \Carbon\Carbon::now()->startOfMonth();
                                $isActive = $now->between($startM, $endM->endOfMonth());
                            @endphp
                            <tr>
                                <td>{{ $childAllowances->firstItem() + $index }}</td>
                                @if(!$isEmployeeRole)
                                    <td>
                                        <strong>{{ $item->user->name ?? 'N/A' }} {{ $item->user->last_name ?? '' }}</strong>
                                        <div class="text-muted small">{{ $item->user->emp_id ?? 'ID N/A' }} • {{ $item->user->designation->desi_name ?? '' }}</div>
                                    </td>
                                @endif
                                <td><strong>{{ $item->child_name }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($item->child_dob)->format('d M, Y') }}</td>
                                <td>{{ $item->start_age }} Yrs</td>
                                <td>{{ $startM->format('M, Y') }}</td>
                                <td>{{ $item->pay_year }} Yrs</td>
                                <td>{{ $endM->format('M, Y') }}</td>
                                <td><strong style="color: #2b6cb0;">{{ number_format($item->pay_amt, 2) }}</strong></td>
                                <td>
                                    @if($isActive)
                                        <span class="status-pill status-active"><i class="im im-icon-Yes"></i> Active</span>
                                    @else
                                        <span class="status-pill status-expired">Expired</span>
                                    @endif
                                </td>
                                @if(!$isEmployeeRole)
                                <td class="text-end">
                                    <button type="button" class="btn btn-outline-info btn-sm edit-ca-btn" data-id="{{ $item->id }}">
                                        <i class="im im-icon-Edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm delete-ca-btn" data-id="{{ $item->id }}">
                                        <i class="im im-icon-Close"></i>
                                    </button>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isEmployeeRole ? 9 : 11 }}" class="text-center py-4 text-muted">
                                    <i class="im im-icon-Information fa-2x mb-2 d-block"></i>
                                    No child allowance details found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($childAllowances->hasPages())
        <div class="card-footer bg-white border-top-0 d-flex justify-content-end">
            {{ $childAllowances->links() }}
        </div>
        @endif
    </div>
</div>

@if(!$isEmployeeRole)
{{-- Add / Edit Child Allowance Modal (Visible to Admin/HR only) --}}
<div class="modal fade" id="childAllowanceModal" tabindex="-1" aria-labelledby="caModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="caModalForm">
                @csrf
                <input type="hidden" name="id" id="ca_id">

                <div class="modal-header" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: #ffffff;">
                    <h5 class="modal-title" id="caModalLabel" style="color: #ffffff;"><i class="im im-icon-User"></i> Child Allowance Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ca_user_id" class="form-label font-weight-bold">Select Employee <span class="text-danger">*</span></label>
                        <select name="user_id" id="ca_user_id" class="form-select" required>
                            <option value="">-- Choose Employee --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} {{ $u->last_name }} ({{ $u->emp_id ?? 'No ID' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ca_child_name" class="form-label font-weight-bold">Child Name <span class="text-danger">*</span></label>
                            <input type="text" name="child_name" id="ca_child_name" class="form-control" required placeholder="Full Name of Child">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ca_child_dob" class="form-label font-weight-bold">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="child_dob" id="ca_child_dob" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ca_start_age" class="form-label font-weight-bold">Start Age (Years)</label>
                            <input type="number" name="start_age" id="ca_start_age" class="form-control" value="5" min="0" max="25">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ca_start_month" class="form-label font-weight-bold">Start Month <span class="text-danger">*</span></label>
                            <input type="month" name="start_month" id="ca_start_month" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ca_pay_year" class="form-label font-weight-bold">Pay Duration (Years)</label>
                            <input type="number" name="pay_year" id="ca_pay_year" class="form-control" value="12" min="1" max="25">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ca_end_month" class="form-label font-weight-bold">End Month <span class="text-danger">*</span></label>
                            <input type="month" name="end_month" id="ca_end_month" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ca_pay_amt" class="form-label font-weight-bold">Monthly Pay Amount <span class="text-danger">*</span></label>
                        <input type="number" name="pay_amt" id="ca_pay_amt" class="form-control" step="0.01" min="0" required placeholder="0.00">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="ca_save_btn"><i class="im im-icon-Disk"></i> Save Allowance</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const modalEl = document.getElementById('childAllowanceModal');
        const caModal = modalEl ? new bootstrap.Modal(modalEl) : null;

        const dobInput = document.getElementById('ca_child_dob');
        const startAgeInput = document.getElementById('ca_start_age');
        const startMonthInput = document.getElementById('ca_start_month');
        const payYearInput = document.getElementById('ca_pay_year');
        const endMonthInput = document.getElementById('ca_end_month');

        function updateStartMonth() {
            if (!dobInput || !dobInput.value) return;
            const dob = new Date(dobInput.value);
            const age = parseInt(startAgeInput.value) || 0;
            if (!isNaN(dob.getTime())) {
                const target = new Date(dob.setFullYear(dob.getFullYear() + age));
                startMonthInput.value = target.toISOString().slice(0, 7);
                updateEndMonth();
            }
        }

        function updateEndMonth() {
            if (!startMonthInput || !startMonthInput.value) return;
            const startM = new Date(startMonthInput.value + '-01');
            const years = parseInt(payYearInput.value) || 0;
            if (!isNaN(startM.getTime())) {
                const targetEnd = new Date(startM.setFullYear(startM.getFullYear() + years));
                endMonthInput.value = targetEnd.toISOString().slice(0, 7);
            }
        }

        if (dobInput) dobInput.addEventListener('change', updateStartMonth);
        if (startAgeInput) startAgeInput.addEventListener('input', updateStartMonth);
        if (startMonthInput) startMonthInput.addEventListener('change', updateEndMonth);
        if (payYearInput) payYearInput.addEventListener('input', updateEndMonth);

        $('#btn-add-allowance').click(function() {
            $('#caModalForm')[0].reset();
            $('#ca_id').val('');
            $('#caModalLabel').html('<i class="im im-icon-Add"></i> Add Child Allowance');
            if (caModal) caModal.show();
        });

        $('#caModalForm').submit(function(e) {
            e.preventDefault();
            const caId = $('#ca_id').val();
            const url = caId ? `/childAllowances/${caId}` : '/childAllowances';
            const formData = new FormData(this);

            if (caId) {
                formData.append('_method', 'PATCH');
            }

            $('#ca_save_btn').prop('disabled', true).text('Saving...');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (caModal) caModal.hide();
                    location.reload();
                },
                error: function(xhr) {
                    $('#ca_save_btn').prop('disabled', false).text('Save Allowance');
                    let msg = 'Error saving record.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        msg = xhr.responseJSON.error;
                    }
                    alert(msg);
                }
            });
        });

        $(document).on('click', '.edit-ca-btn', function() {
            const id = $(this).data('id');
            $.ajax({
                url: `/childAllowances/${id}/edit`,
                type: 'GET',
                success: function(res) {
                    if (res.childAllowance) {
                        const ca = res.childAllowance;
                        $('#ca_id').val(ca.id);
                        if ($('#ca_user_id').is('select')) {
                            $('#ca_user_id').val(ca.user_id);
                        }
                        $('#ca_child_name').val(ca.child_name);
                        $('#ca_child_dob').val(ca.child_dob);
                        $('#ca_start_age').val(ca.start_age);
                        
                        let sm = ca.start_month ? ca.start_month.substring(0, 7) : '';
                        let em = ca.end_month ? ca.end_month.substring(0, 7) : '';
                        $('#ca_start_month').val(sm);
                        $('#ca_pay_year').val(ca.pay_year);
                        $('#ca_end_month').val(em);
                        $('#ca_pay_amt').val(ca.pay_amt);

                        $('#caModalLabel').html('<i class="im im-icon-Edit"></i> Edit Child Allowance');
                        if (caModal) caModal.show();
                    }
                },
                error: function(xhr) {
                    alert('Could not fetch allowance details.');
                }
            });
        });

        $(document).on('click', '.delete-ca-btn', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this child allowance record?')) {
                $.ajax({
                    url: `/childAllowances/${id}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Could not delete allowance record.');
                    }
                });
            }
        });
    });
</script>
@endpush
@endif
@endsection
