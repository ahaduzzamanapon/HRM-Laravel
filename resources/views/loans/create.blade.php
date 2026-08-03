@extends('layouts.default')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="im im-icon-Coins me-2"></i>Apply For Employee Loan</h5>
                </div>
                <div class="card-body p-4">
                    @include('flash::message')

                    <form action="{{ route('employeeLoans.store') }}" method="POST">
                        @csrf

                        @if($canManageLoans && !empty($employees))
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Employee</label>
                                <select name="employee_id" class="form-select select2-loan-search" required>
                                    <option value="">-- Apply for Self --</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }} {{ $emp->last_name }} (ID: {{ $emp->emp_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Loan Category / Type</label>
                            <select name="loan_type_id" id="loan_type_id" class="form-select" required>
                                <option value="">-- Select Loan Type --</option>
                                @foreach($loanTypes as $type)
                                    <option value="{{ $type->id }}" data-rate="{{ $type->interest_rate ?? 5 }}" data-max="{{ $type->max_installments ?? 60 }}">
                                        {{ $type->name }} (Rate: {{ $type->interest_rate ?? 5 }}%, Max Term: {{ $type->max_installments ?? 60 }} Months)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Requested Amount (৳)</label>
                                <input type="number" name="amount" id="amount" class="form-control" placeholder="100000" min="1000" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Installment Months</label>
                                <input type="number" name="installments" id="installments" class="form-control" placeholder="24" min="1" max="120" required>
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
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Reason for loan application..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('employeeLoans.index') }}" class="btn btn-secondary rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-success rounded-pill px-4"><i class="im im-icon-Check me-1"></i> Submit Application</button>
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
    $(document).ready(function() {
        if ($('.select2-loan-search').length) {
            $('.select2-loan-search').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Apply for Self / Select Employee --'
            });
        }
    });

    $('#amount, #installments, #loan_type_id').on('input change', function() {
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
            $('#emiPreview').slideDown();
        } else {
            $('#emiPreview').slideUp();
        }
    });
</script>
@endpush
@endsection
