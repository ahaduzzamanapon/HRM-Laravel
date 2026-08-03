@extends('layouts.default')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0 fw-bold"><i class="im im-icon-Edit me-2"></i>Edit Loan Application ({{ $loan->application_no ?? 'LN-'.$loan->id }})</h5>
                </div>
                <div class="card-body p-4">
                    @include('flash::message')

                    <form action="{{ route('employeeLoans.update', $loan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if($canManageLoans && !empty($employees))
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Employee</label>
                                <select name="employee_id" class="form-select select2-loan-search" required>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ (string)$loan->employee_id === (string)$emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }} {{ $emp->last_name }} (ID: {{ $emp->emp_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Loan Category / Type</label>
                            <select name="loan_type_id" id="loan_type_id" class="form-select" required>
                                @foreach($loanTypes as $type)
                                    <option value="{{ $type->id }}" data-rate="{{ $type->interest_rate ?? 5 }}" data-max="{{ $type->max_installments ?? 60 }}" {{ (string)$loan->loan_type_id === (string)$type->id ? 'selected' : '' }}>
                                        {{ $type->name }} (Rate: {{ $type->interest_rate ?? 5 }}%, Max Term: {{ $type->max_installments ?? 60 }} Months)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Requested Amount (৳)</label>
                                <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount', $loan->amount) }}" min="1000" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Installment Months</label>
                                <input type="number" name="installments" id="installments" class="form-control" value="{{ old('installments', $loan->installments) }}" min="1" max="120" required>
                            </div>
                        </div>

                        {{-- Dynamic EMI Preview --}}
                        <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4" id="emiPreview">
                            <h6 class="fw-bold text-primary mb-2"><i class="im im-icon-Calculator me-1"></i> Estimated EMI Calculation</h6>
                            <div class="row text-center g-2">
                                <div class="col-4">
                                    <small class="text-muted d-block">Principal</small>
                                    <strong id="previewPrincipal">৳ {{ number_format($loan->amount, 2) }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Est. Interest</small>
                                    <strong id="previewInterest">৳ {{ number_format($loan->outstanding_balance - $loan->amount, 2) }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Monthly EMI</small>
                                    <strong class="text-danger fs-5" id="previewEMI">৳ {{ number_format($loan->monthly_installment, 2) }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Purpose / Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $loan->remarks) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('employeeLoans.index') }}" class="btn btn-secondary rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-warning text-dark rounded-pill px-4"><i class="im im-icon-Check me-1"></i> Update Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        function calculateEMI() {
            const amount = parseFloat($('#amount').val()) || 0;
            const installments = parseInt($('#installments').val()) || 0;
            const selectedOption = $('#loan_type_id option:selected');
            const rate = parseFloat(selectedOption.data('rate')) || 0;

            if (amount > 0 && installments > 0) {
                const totalInterest = (amount * (rate / 100)) * (installments / 12);
                const totalAmount = amount + totalInterest;
                const emi = (totalAmount / installments).toFixed(2);

                $('#previewPrincipal').text('৳ ' + amount.toLocaleString());
                $('#previewInterest').text('৳ ' + totalInterest.toFixed(2).toLocaleString());
                $('#previewEMI').text('৳ ' + emi.toLocaleString());
                $('#emiPreview').show();
            }
        }

        $('#amount, #installments, #loan_type_id').on('input change', calculateEMI);
        calculateEMI();
    });
</script>
@endpush
@endsection
