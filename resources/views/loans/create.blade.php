@extends('layouts.default')

@section('content')
<style>
    /* Loan Application Form Text & Input Color Styling */
    #applyLoanModal .form-label,
    #editLoanModal .form-label,
    #loanApplicationForm .form-label,
    #loanEditForm .form-label,
    #editLoanForm .form-label {
        color: #000000 !important;
        font-weight: 600 !important;
    }

    #applyLoanModal .form-text,
    #editLoanModal .form-text,
    #loanApplicationForm .form-text,
    #loanEditForm .form-text,
    #editLoanForm .form-text {
        color: #333333 !important;
    }

    #applyLoanModal .form-control,
    #applyLoanModal .form-select,
    #editLoanModal .form-control,
    #editLoanModal .form-select,
    #loanApplicationForm .form-control,
    #loanApplicationForm .form-select,
    #loanEditForm .form-control,
    #loanEditForm .form-select,
    #editLoanForm .form-control,
    #editLoanForm .form-select {
        color: #000000 !important;
        font-weight: 500 !important;
    }

    /* Muted placeholder style */
    #applyLoanModal .form-control::placeholder,
    #applyLoanModal textarea::placeholder,
    #editLoanModal .form-control::placeholder,
    #editLoanModal textarea::placeholder,
    #loanApplicationForm .form-control::placeholder,
    #loanApplicationForm textarea::placeholder,
    #loanEditForm .form-control::placeholder,
    #loanEditForm textarea::placeholder,
    #editLoanForm .form-control::placeholder,
    #editLoanForm textarea::placeholder {
        color: #6c757d !important;
        opacity: 0.8 !important;
        font-weight: 400 !important;
    }

    #applyLoanModal .form-control::-webkit-input-placeholder,
    #editLoanModal .form-control::-webkit-input-placeholder,
    #loanApplicationForm .form-control::-webkit-input-placeholder,
    #loanEditForm .form-control::-webkit-input-placeholder,
    #editLoanForm .form-control::-webkit-input-placeholder {
        color: #6c757d !important;
        opacity: 0.8 !important;
        font-weight: 400 !important;
    }

    /* Select2 dropdown text & placeholder */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered,
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #000000 !important;
        font-weight: 500 !important;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__placeholder,
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d !important;
        font-weight: 400 !important;
    }

    .select2-container--bootstrap-5 .select2-dropdown .select2-results__option,
    .select2-container--default .select2-dropdown .select2-results__option {
        color: #000000 !important;
    }
</style>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header py-3" style="background: aliceblue; border-bottom: 1px solid #cce5ff;">
                    <h5 class="mb-0 fw-bold" style="color: #0177bc;"><i class="im im-icon-Coins me-2"></i>Apply For Employee Loan</h5>
                </div>
                <div class="card-body p-4">
                    @include('flash::message')

                    <form action="{{ route('employeeLoans.store') }}" method="POST" id="loanApplicationForm">
                        @csrf

                        @if(!empty($canManageLoans) && $canManageLoans && !empty($employees) && count($employees) > 0)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Employee</label>
                                <select name="employee_id" id="employee_id" class="form-select select2-loan-search">
                                    <option value="" data-grade="{{ auth()->user()->salaryGrade->grade ?? '' }}">-- Apply for Self / Select Employee --</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" 
                                                data-grade="{{ $emp->salaryGrade->grade ?? '' }}"
                                                {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }} {{ $emp->last_name }} (ID: {{ $emp->emp_id }}) {{ $emp->salaryGrade ? '['.$emp->salaryGrade->grade.']' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Loan Category / Type</label>
                            <select name="loan_type_id" id="loan_type_id" class="form-select" required>
                                <option value="">-- Select Loan Type --</option>
                                @foreach($loanTypes as $type)
                                    <option value="{{ $type->id }}" 
                                            data-rate="{{ $type->interest_rate ?? 5 }}" 
                                            data-max="{{ $type->max_installments ?? '' }}"
                                            data-ceilings="{{ json_encode($type->loan_ceilings ?? []) }}"
                                            {{ old('loan_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} (Rate: {{ $type->interest_rate ?? 5 }}%, Max Term: {{ $type->max_installments ? $type->max_installments.' Months' : 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Requested Amount (৳)</label>
                                <input type="number" name="amount" id="amount" class="form-control" placeholder="100000" min="1000" value="{{ old('amount') }}" required>
                                <div id="amountLimitInfo" class="form-text mt-1 text-primary fw-medium"></div>
                                <div id="amountWarning" class="invalid-feedback fw-bold mt-1"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Installment Months</label>
                                <input type="number" name="installments" id="installments" class="form-control" placeholder="24" min="1" value="{{ old('installments') }}" required>
                                <div id="installmentsLimitInfo" class="form-text mt-1 text-primary fw-medium"></div>
                                <div id="installmentsWarning" class="invalid-feedback fw-bold mt-1"></div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Loan Required Month <span class="text-danger">*</span></label>
                                <input type="month" name="required_month" id="required_month" class="form-control" value="{{ old('required_month', date('Y-m')) }}" required>
                                <div class="form-text text-muted">Month when loan disbursement is needed.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Loan Effective Month <span class="text-danger">*</span></label>
                                <input type="month" name="effective_month" id="effective_month" class="form-control" value="{{ old('effective_month', date('Y-m', strtotime('+1 month'))) }}" required>
                                <div class="form-text text-muted">First payroll month for salary deduction.</div>
                                <div id="effectiveMonthWarning" class="invalid-feedback fw-bold mt-1"></div>
                            </div>
                        </div>

                        {{-- Dynamic EMI Preview --}}
                        <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4" id="emiPreview" style="display:none;">
                            <h6 class="fw-bold text-primary mb-2"><i class="im im-icon-Calculator me-1"></i> Estimated EMI Calculation</h6>
                            <div class="row text-center g-2">
                                <div class="col-4">
                                    <small class="text-muted d-block">Principal</small>
                                    <strong id="previewPrincipal">৳ 0</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Est. Interest</small>
                                    <strong id="previewInterest">৳ 0</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Monthly EMI</small>
                                    <strong class="text-danger fs-5" id="previewEMI">৳ 0</strong>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Purpose / Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Reason for loan application...">{{ old('remarks') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('employeeLoans.index') }}" class="btn btn-secondary rounded-pill px-4">Cancel</a>
                            <button type="submit" id="submitBtn" class="btn btn-success rounded-pill px-4"><i class="im im-icon-Check me-1"></i> Submit Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    window.authGrade = "{{ auth()->user()->salaryGrade->grade ?? '' }}";

    $(document).ready(function() {
        if ($('.select2-loan-search').length) {
            $('.select2-loan-search').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Apply for Self / Select Employee --'
            });
        }

        validateCategoryLimits();
    });

    function validateCategoryLimits() {
        const selectedType = $('#loan_type_id option:selected');
        const typeId = $('#loan_type_id').val();
        const amt = parseFloat($('#amount').val()) || 0;
        const months = parseInt($('#installments').val()) || 0;
        const reqMonth = $('#required_month').val();
        const effMonth = $('#effective_month').val();

        // Reset previous validation error messages
        $('#amountWarning, #installmentsWarning, #effectiveMonthWarning').html('');
        $('#amount, #installments, #effective_month').removeClass('is-invalid');

        // Month sequence check
        if (reqMonth && effMonth && effMonth < reqMonth) {
            $('#effective_month').addClass('is-invalid');
            $('#effectiveMonthWarning').html('⚠️ Loan Effective Month cannot be earlier than Loan Required Month!');
        }

        if (!typeId || !selectedType.length) {
            $('#amountLimitInfo, #installmentsLimitInfo').hide().html('');
            return;
        }

        const maxTerm = parseInt(selectedType.data('max')) || 0;
        let rawCeilings = selectedType.data('ceilings');
        if (typeof rawCeilings === 'string') {
            try { rawCeilings = JSON.parse(rawCeilings); } catch(e) { rawCeilings = []; }
        }

        // 1. Installment Limit Info & Warning
        if (maxTerm > 0) {
            $('#installments').attr('max', maxTerm);
            $('#installmentsLimitInfo').html('<i class="im im-icon-Information me-1"></i> Category Max Term: <strong>' + maxTerm + ' Months</strong>').show();
            
            if (months > maxTerm) {
                $('#installments').addClass('is-invalid');
                $('#installmentsWarning').html('⚠️ Entered installments (' + months + ' months) exceeds maximum limit of ' + maxTerm + ' months for this category!');
            }
        } else {
            $('#installments').removeAttr('max');
            $('#installmentsLimitInfo').hide().html('');
        }

        // 2. Employee Grade & Ceiling Info & Warning
        let selectedEmpOption = $('#employee_id option:selected');
        let empGrade = (selectedEmpOption.length && $('#employee_id').val()) 
            ? (selectedEmpOption.data('grade') || '') 
            : window.authGrade;

        let maxCeiling = null;
        let ceilingGradeLabel = null;

        if (Array.isArray(rawCeilings) && rawCeilings.length > 0) {
            if (empGrade) {
                for (let i = 0; i < rawCeilings.length; i++) {
                    let c = rawCeilings[i];
                    if (c && c.grade && c.amount) {
                        let cG = String(c.grade).trim().toLowerCase().replace('grade ', '');
                        let eG = String(empGrade).trim().toLowerCase().replace('grade ', '');
                        if (cG === eG) {
                            maxCeiling = parseFloat(c.amount);
                            ceilingGradeLabel = c.grade;
                            break;
                        }
                    }
                }
            }

            if (maxCeiling === null) {
                for (let i = 0; i < rawCeilings.length; i++) {
                    let a = parseFloat(rawCeilings[i].amount);
                    if (!isNaN(a) && (maxCeiling === null || a > maxCeiling)) {
                        maxCeiling = a;
                    }
                }
            }
        }

        if (maxCeiling !== null && maxCeiling > 0) {
            let gradeInfoStr = ceilingGradeLabel ? (' for ' + ceilingGradeLabel) : (empGrade ? (' for ' + empGrade) : '');
            $('#amountLimitInfo').html('<i class="im im-icon-Information me-1"></i> Max Loan Ceiling' + gradeInfoStr + ': <strong>৳ ' + maxCeiling.toLocaleString() + '</strong>').show();

            if (amt > maxCeiling) {
                $('#amount').addClass('is-invalid');
                $('#amountWarning').html('⚠️ Requested amount (৳ ' + amt.toLocaleString() + ') exceeds maximum ceiling limit of ৳ ' + maxCeiling.toLocaleString() + gradeInfoStr + '!');
            }
        } else {
            $('#amountLimitInfo').hide().html('');
        }
    }

    $('#loan_type_id, #employee_id, #amount, #installments, #required_month, #effective_month').on('input change select2:select', function() {
        validateCategoryLimits();

        let amt = parseFloat($('#amount').val()) || 0;
        let months = parseInt($('#installments').val()) || 0;
        let rate = parseFloat($('#loan_type_id option:selected').data('rate')) || 5;

        if (amt > 0 && months > 0) {
            let totalInterest = (amt * (rate / 100)) * (months / 12);
            let totalPayable = amt + totalInterest;
            let emi = totalPayable / months;

            $('#previewPrincipal').text('৳ ' + amt.toLocaleString());
            $('#previewInterest').text('৳ ' + totalInterest.toFixed(2));
            $('#previewEMI').text('৳ ' + emi.toFixed(2));
            $('#emiPreview').show();
        } else {
            $('#emiPreview').hide();
        }
    });
</script>
@endpush
@endsection
