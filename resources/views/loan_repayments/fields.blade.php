<!-- Loan Application Selection & Summary Card -->
<div class="col-12 mb-4">
    <div class="card border border-primary-subtle bg-light rounded-3 p-3 shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-12 mb-3">
                <label for="loan_id_select" class="form-label fw-bold text-dark fs-6">
                    <i class="im im-icon-Coins me-2 text-primary"></i>Select Loan Application ID <span class="text-danger">*</span>
                </label>
                {!! Form::select('loan_id', $loansMap ?? [], null, ['class' => 'form-select form-select-lg select2', 'id' => 'loan_id_select', 'required', 'placeholder' => '-- Search & Select Loan Application ID --', 'style' => 'width: 100%;']) !!}
                <small class="text-muted d-block mt-1">Select an active or approved loan application. Outstanding balance and repayment info will load automatically.</small>
            </div>
        </div>

        <!-- Dynamic Loan Application Info Card -->
        <div id="loan-info-card" class="mt-3 p-3 bg-white rounded-3 border d-none">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                <h6 class="m-0 fw-bold text-primary d-flex align-items-center gap-2">
                    <i class="im im-icon-ID-Card"></i>
                    <span id="info_app_no">LN-000000</span> — <span id="info_emp_name">Employee Name</span>
                </h6>
                <span id="info_status_badge" class="badge bg-success fs-6">Disbursed</span>
            </div>

            <div class="row g-3 text-dark">
                <div class="col-md-3 col-6">
                    <small class="text-muted d-block fw-semibold">Employee ID & Branch</small>
                    <span id="info_emp_id_branch" class="fw-bold">EMP-001 (Branch)</span>
                </div>
                <div class="col-md-3 col-6">
                    <small class="text-muted d-block fw-semibold">Loan Category / Type</small>
                    <span id="info_loan_type" class="badge bg-info text-dark">Staff Loan</span>
                </div>
                <div class="col-md-3 col-6">
                    <small class="text-muted d-block fw-semibold">Approved Loan Amount</small>
                    <span id="info_principal_amount" class="fw-bold text-dark fs-6">৳ 0.00</span>
                </div>
                <div class="col-md-3 col-6">
                    <small class="text-muted d-block fw-semibold">Repayment Term & Monthly EMI</small>
                    <span id="info_emi_amount" class="fw-bold text-danger fs-6">৳ 0.00 / Month</span>
                </div>
            </div>

            <div class="row g-3 mt-2 pt-2 border-top">
                <div class="col-md-4 col-6">
                    <div class="p-2 bg-success-subtle bg-opacity-20 rounded border border-success-subtle text-center">
                        <small class="text-muted d-block font-semibold">Total Paid Amount</small>
                        <strong id="info_total_paid" class="text-success fs-6">৳ 0.00</strong>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="p-2 bg-danger-subtle bg-opacity-20 rounded border border-danger-subtle text-center">
                        <small class="text-muted d-block font-semibold">Outstanding Balance</small>
                        <strong id="info_outstanding_balance" class="text-danger fs-6">৳ 0.00</strong>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="p-2 bg-primary-subtle bg-opacity-20 rounded border border-primary-subtle text-center">
                        <small class="text-muted d-block font-semibold">Loan Effective Month</small>
                        <strong id="info_effective_month" class="text-primary fs-6">N/A</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Repayment Amount Field -->
<div class="col-md-6 mb-3">
    <div class="form-group">
        {!! Form::label('amount', 'Repayment Amount (৳):', ['class' => 'fw-bold mb-1 text-dark']) !!} <span class="text-danger">*</span>
        {!! Form::number('amount', null, ['class' => 'form-control form-control-lg', 'id' => 'repayment_amount_input', 'step' => '0.01', 'min' => 1, 'required', 'placeholder' => 'Enter repayment amount']) !!}
        <small class="text-muted">Auto-filled with monthly EMI. You may adjust for partial or overdue payments.</small>
    </div>
</div>

<!-- Repayment Date Field -->
<div class="col-md-6 mb-3">
    <div class="form-group">
        {!! Form::label('repayment_date', 'Repayment Date & Time:', ['class' => 'fw-bold mb-1 text-dark']) !!} <span class="text-danger">*</span>
        {!! Form::text('repayment_date', isset($loanRepayment) ? $loanRepayment->repayment_date : \Carbon\Carbon::now()->format('Y-m-d H:i:s'), ['class' => 'form-control form-control-lg', 'id' => 'repayment_date_input', 'required']) !!}
        <small class="text-muted">Auto-set to current date. Adjust manually if recording a custom date repayment.</small>
    </div>
</div>

<!-- Remarks Field -->
<div class="col-md-12 mb-3">
    <div class="form-group">
        {!! Form::label('remarks', 'Repayment Reference / Notes:', ['class' => 'fw-bold mb-1 text-dark']) !!}
        {!! Form::textarea('remarks', null, ['class' => 'form-control', 'id' => 'remarks_input', 'rows' => 3, 'placeholder' => 'Enter payment reference or notes (e.g. Monthly installment #1)...']) !!}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 text-end mt-4 pt-3 border-top">
    {!! Form::submit('Save Loan Repayment', ['class' => 'btn btn-primary btn-lg px-5 shadow-sm', 'id' => 'submit_btn']) !!}
    <a href="{{ route('loanRepayments.index') }}" class="btn btn-secondary btn-lg px-4 ms-2">Cancel</a>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var loansDetails = @json($loansDetailsMap ?? []);

            function formatCurrency(num) {
                return '৳ ' + parseFloat(num || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function updateLoanSummaryCard() {
                var selectedId = $('#loan_id_select').val();

                if (selectedId && loansDetails[selectedId]) {
                    var details = loansDetails[selectedId];

                    $('#info_app_no').text(details.application_no);
                    $('#info_emp_name').text(details.employee_name);
                    $('#info_emp_id_branch').text(details.emp_id + ' (' + details.branch_name + ')');
                    $('#info_loan_type').text(details.loan_type_name);
                    $('#info_principal_amount').text(formatCurrency(details.principal_amount));
                    $('#info_emi_amount').text(formatCurrency(details.monthly_installment) + ' / Month (' + details.installments + ' M)');
                    $('#info_total_paid').text(formatCurrency(details.total_paid));
                    $('#info_outstanding_balance').text(formatCurrency(details.outstanding_balance));
                    $('#info_effective_month').text(details.effective_month);

                    var badgeClass = 'bg-secondary';
                    if (details.status === 'Approved') badgeClass = 'bg-info text-white';
                    else if (details.status === 'Disbursed') badgeClass = 'bg-success';
                    else if (details.status === 'Active Repayment') badgeClass = 'bg-primary';
                    else if (details.status === 'Completed' || details.status === 'Repaid') badgeClass = 'bg-dark';

                    $('#info_status_badge').removeClass().addClass('badge fs-6 ' + badgeClass).text(details.status);

                    $('#loan-info-card').removeClass('d-none');

                    // Auto-fill repayment amount if input is empty or zero
                    var currentAmt = $('#repayment_amount_input').val();
                    if (!currentAmt || parseFloat(currentAmt) === 0) {
                        var suggestAmt = (details.monthly_installment > 0 && details.monthly_installment <= details.outstanding_balance)
                            ? details.monthly_installment
                            : details.outstanding_balance;
                        $('#repayment_amount_input').val(suggestAmt.toFixed(2));
                    }

                    // Auto-fill remarks if empty
                    var currentRemarks = $('#remarks_input').val();
                    if (!currentRemarks) {
                        $('#remarks_input').val('Monthly installment repayment for ' + details.application_no);
                    }

                } else {
                    $('#loan-info-card').addClass('d-none');
                }
            }

            $('#loan_id_select').on('change', function() {
                updateLoanSummaryCard();
            });

            // Initialize select2 if available
            if (typeof $.fn.select2 !== 'undefined') {
                $('#loan_id_select').select2({
                    placeholder: '-- Search & Select Loan Application ID --',
                    allowClear: true
                });
            }

            updateLoanSummaryCard();
        });
    </script>
@endpush
