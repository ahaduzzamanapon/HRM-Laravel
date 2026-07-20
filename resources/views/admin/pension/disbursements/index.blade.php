@extends('layouts.default')

@section('title')
Pension Disbursements @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Pension Disbursements</h3>
            </div>
            <div class="col-sm-6 text-right">
                <form action="{{ route('admin.pension.disbursements.process') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Run monthly disbursement for all approved pensioners?')">
                        <i class="fa fa-credit-card"></i> Run Monthly Disbursement
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="clearfix"></div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Employee</th>
                            <th>Month</th>
                            <th>Method</th>
                            <th>Bank Ref</th>
                            <th>Payment Date</th>
                            <!-- <th>Net Payable</th> -->
                            <th>Total Pension</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($disbursements as $disbursement)
                        @php
                            $pensionPercentage = ($disbursement->profile->last_basic_pay ?? 0) > 0 
                                ? (($disbursement->profile->calculation->gross_pension ?? 0) / $disbursement->profile->last_basic_pay) * 100 
                                : 0;
                        @endphp
                        <tr>
                            <td class="align-middle">{{ ($disbursements->currentPage() - 1) * $disbursements->perPage() + $loop->iteration }}</td>
                            <td class="align-middle text-left pl-3">
                                <strong>{{ $disbursement->profile->user->name ?? 'N/A' }} {{ $disbursement->profile->user->last_name ?? '' }}</strong>
                            </td>
                            <td class="align-middle">{{ $disbursement->disbursement_month }}</td>
                            <td class="align-middle">{{ $disbursement->payment_method }}</td>
                            <td class="align-middle">{{ $disbursement->bank_reference ?? 'N/A' }}</td>
                            <td class="align-middle">
                                @if($disbursement->paid_at)
                                    <span >{{ $disbursement->paid_at->format('d M Y') }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="align-middle   text-success">
                                {{ number_format(($disbursement->profile->calculation->commuted_amount ?? 0) + ($disbursement->profile->calculation->net_pension ?? 0), 2) }}
                            </td>
                            <!-- <td class="align-middle text-success fw-bold">{{ number_format($disbursement->net_payable, 2) }}</td> -->
                            <td class="align-middle">
                                @php
                                    $badgeClass = 'badge-secondary';
                                    if($disbursement->status == 'Paid') $badgeClass = 'badge-success';
                                    if($disbursement->status == 'Processing') $badgeClass = 'badge-info';
                                    if($disbursement->status == 'Pending') $badgeClass = 'badge-warning';
                                    if($disbursement->status == 'Failed') $badgeClass = 'badge-danger';
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $disbursement->status }}</span>
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('admin.pension.disbursements.payslip', $disbursement->id) }}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Download Payslip">
                                    <i class="fa fa-download"></i>
                                </a>
                                @if($disbursement->status !== 'Paid')
                                <button type="button" class="btn btn-sm btn-outline-success ml-1" 
                                        data-toggle="modal" 
                                        data-target="#paymentModal"
                                        data-action="{{ route('admin.pension.disbursements.pay', $disbursement->id) }}"
                                        data-employee="{{ $disbursement->profile->user->name ?? 'N/A' }} {{ $disbursement->profile->user->last_name ?? '' }}"
                                        title="Mark as Paid">
                                    <i class="fa fa-check"></i>
                                </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-info ml-1" 
                                        data-toggle="modal" 
                                        data-target="#editModal"
                                        data-action="{{ route('admin.pension.disbursements.update', $disbursement->id) }}"
                                        data-employee="{{ $disbursement->profile->user->name ?? 'N/A' }} {{ $disbursement->profile->user->last_name ?? '' }}"
                                        data-method="{{ $disbursement->payment_method }}"
                                        data-status="{{ $disbursement->status }}"
                                        data-bankref="{{ $disbursement->bank_reference ?? '' }}"
                                        data-paidat="{{ $disbursement->paid_at ? $disbursement->paid_at->format('Y-m-d') : '' }}"
                                        title="Edit Disbursement">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary ml-1" 
                                        data-toggle="modal" 
                                        data-target="#viewModal"
                                        data-employee="{{ $disbursement->profile->user->name ?? 'N/A' }} {{ $disbursement->profile->user->last_name ?? '' }}"
                                        data-empid="{{ $disbursement->profile->user->emp_id ?? 'N/A' }}"
                                        data-month="{{ $disbursement->disbursement_month }}"
                                        data-method="{{ $disbursement->payment_method }}"
                                        data-status="{{ $disbursement->status }}"
                                        data-bankref="{{ $disbursement->bank_reference ?? 'N/A' }}"
                                        data-paidat="{{ $disbursement->paid_at ? $disbursement->paid_at->format('d M Y') : 'N/A' }}"
                                        data-retirement="{{ $disbursement->profile->retirement_date ?? 'N/A' }}"
                                        data-scheme="{{ $disbursement->profile->scheme->name ?? 'N/A' }}"
                                        data-basic="{{ number_format($disbursement->profile->last_basic_pay ?? 0, 2) }}"
                                        data-percentage="{{ number_format($pensionPercentage, 2) }}"
                                        data-gross="{{ number_format($disbursement->profile->calculation->gross_pension ?? 0, 2) }}"
                                        data-commuted="{{ number_format($disbursement->profile->calculation->commuted_amount ?? 0, 2) }}"
                                        data-monthly="{{ number_format($disbursement->profile->calculation->monthly_pension ?? 0, 2) }}"
                                        data-medical="{{ number_format($disbursement->profile->calculation->medical_allowance ?? 1500, 2) }}"
                                        data-netpayable="{{ number_format($disbursement->net_payable, 2) }}"
                                        data-totalpension="{{ number_format(($disbursement->profile->calculation->commuted_amount ?? 0) + ($disbursement->profile->calculation->net_pension ?? 0), 2) }}"
                                        title="View Details">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fa fa-folder-open fa-3x mb-3 d-block"></i>
                                No disbursements found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            <div class="float-right">
                {{ $disbursements->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Record Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="paymentModalLabel"><i class="fa fa-credit-card mr-2"></i> Record Pension Payment</h5>
                <button type="button" class="close text-white" data-dismiss="close" data-target="#paymentModal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="paymentForm" action="" method="POST">
                @csrf
                <div class="modal-body text-left">
                    <p>Are you sure you want to mark the pension disbursement for <strong id="paymentEmployeeName"></strong> as Paid?</p>
                    
                    <div class="form-group">
                        <label for="paid_at" class="font-weight-bold">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="paid_at" name="paid_at" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="bank_reference" class="font-weight-bold">Bank Reference (Optional)</label>
                        <input type="text" class="form-control" id="bank_reference" name="bank_reference" placeholder="Enter bank reference number">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Payment Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="editModalLabel"><i class="fa fa-edit mr-2"></i> Edit Pension Disbursement</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body text-left">
                    <p>Editing disbursement details for <strong id="editEmployeeName"></strong>.</p>
                    
                    <div class="form-group">
                        <label for="edit_payment_method" class="font-weight-bold">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_payment_method" name="payment_method" required>
                            <option value="EFT">EFT</option>
                            <option value="BEFTN">BEFTN</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_status" class="font-weight-bold">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Processing">Processing</option>
                            <option value="Paid">Paid</option>
                            <option value="Failed">Failed</option>
                        </select>
                    </div>

                    <div class="form-group" id="edit_bank_ref_group">
                        <label for="edit_bank_reference" class="font-weight-bold">Bank Reference</label>
                        <input type="text" class="form-control" id="edit_bank_reference" name="bank_reference" placeholder="Enter bank reference number">
                    </div>

                    <div class="form-group" id="edit_paid_at_group">
                        <label for="edit_paid_at" class="font-weight-bold">Payment Date</label>
                        <input type="date" class="form-control" id="edit_paid_at" name="paid_at">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info text-white">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title text-light" id="viewModalLabel"><i class="fa fa-info-circle mr-2"></i> Pension Disbursement Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-left">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-primary border-bottom pb-2 font-weight-bold">Employee Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="font-weight-bold" style="width: 40%;">Name:</td>
                                <td id="view_employee_name"></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Employee ID:</td>
                                <td id="view_emp_id"></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Retirement Date:</td>
                                <td id="view_retirement_date"></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Pension Scheme:</td>
                                <td id="view_scheme"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary border-bottom pb-2 font-weight-bold">Disbursement Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="font-weight-bold" style="width: 40%;">Month:</td>
                                <td id="view_month" class="font-weight-bold"></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Payment Method:</td>
                                <td id="view_payment_method"></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Bank Reference:</td>
                                <td id="view_bank_reference"></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Payment Date:</td>
                                <td id="view_paid_at"></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Status:</td>
                                <td><span id="view_status" class="badge px-3 py-1 rounded-pill"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <h6 class="text-primary border-bottom pb-2 font-weight-bold">Financial Breakdown</h6>
                <table class="table table-sm table-bordered text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>Description</th>
                            <th style="width: 200px;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-left pl-3">
                                <div class="font-weight-bold">Gross Monthly Pension</div>
                                <small class="text-muted d-block" id="formula_gross"></small>
                            </td>
                            <td class="text-right pr-3 font-weight-bold text-secondary align-middle"><span id="view_gross_pension"></span></td>
                        </tr>
                        <tr>
                            <td class="text-left pl-3">
                                <div class="font-weight-bold">Commuted Amount (Lump Sum Gratuity)</div>
                                <small class="text-muted d-block" id="formula_commuted"></small>
                            </td>
                            <td class="text-right pr-3 font-weight-bold text-secondary align-middle"><span id="view_commuted_amount"></span></td>
                        </tr>
                        <tr>
                            <td class="text-left pl-3">
                                <div class="font-weight-bold">Monthly Pension (Remaining 50%)</div>
                                <small class="text-muted d-block" id="formula_monthly"></small>
                            </td>
                            <td class="text-right pr-3 font-weight-bold text-secondary align-middle"><span id="view_monthly_pension"></span></td>
                        </tr>
                        <tr>
                            <td class="text-left pl-3">
                                <div class="font-weight-bold">Medical Allowance</div>
                                <small class="text-muted d-block" id="formula_medical"></small>
                            </td>
                            <td class="text-right pr-3 font-weight-bold text-secondary align-middle"><span id="view_medical_allowance"></span></td>
                        </tr>
                        <tr class="bg-light">
                            <td class="text-left pl-3 font-weight-bold">
                                <div>Net Monthly Payable</div>
                                <small class="text-muted d-block font-weight-normal" id="formula_net"></small>
                            </td>
                            <td class="text-right pr-3 font-weight-bold text-primary align-middle"><span id="view_net_payable"></span></td>
                        </tr>
                        <tr class="bg-light font-weight-bold">
                            <td class="text-left pl-3">
                                <div>Total Package (One-time Lump Sum + Net Monthly)</div>
                                <small class="text-muted d-block font-weight-normal" id="formula_total"></small>
                            </td>
                            <td class="text-right pr-3 text-success align-middle"><span id="view_total_pension"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Record payment modal
        $('#paymentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var actionUrl = button.data('action');
            var employeeName = button.data('employee');
            
            var modal = $(this);
            modal.find('#paymentForm').attr('action', actionUrl);
            modal.find('#paymentEmployeeName').text(employeeName);
            
            modal.find('#bank_reference').val('');
            var today = new Date().toISOString().split('T')[0];
            modal.find('#paid_at').val(today);
        });

        // Edit modal
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var actionUrl = button.data('action');
            var employeeName = button.data('employee');
            var method = button.data('method');
            var status = button.data('status');
            var bankRef = button.data('bankref');
            var paidAt = button.data('paidat');
            
            var modal = $(this);
            modal.find('#editForm').attr('action', actionUrl);
            modal.find('#editEmployeeName').text(employeeName);
            modal.find('#edit_payment_method').val(method);
            modal.find('#edit_status').val(status);
            modal.find('#edit_bank_reference').val(bankRef);
            
            if (paidAt) {
                modal.find('#edit_paid_at').val(paidAt);
            } else {
                var today = new Date().toISOString().split('T')[0];
                modal.find('#edit_paid_at').val(today);
            }

            toggleEditFields(status);
        });

        // View modal
        $('#viewModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            
            var modal = $(this);
            modal.find('#view_employee_name').text(button.data('employee'));
            modal.find('#view_emp_id').text(button.data('empid'));
            modal.find('#view_month').text(button.data('month'));
            modal.find('#view_payment_method').text(button.data('method'));
            modal.find('#view_bank_reference').text(button.data('bankref'));
            modal.find('#view_paid_at').text(button.data('paidat'));
            modal.find('#view_retirement_date').text(button.data('retirement'));
            modal.find('#view_scheme').text(button.data('scheme'));
            modal.find('#view_gross_pension').text(button.data('gross'));
            modal.find('#view_commuted_amount').text(button.data('commuted'));
            modal.find('#view_monthly_pension').text(button.data('monthly'));
            modal.find('#view_medical_allowance').text(button.data('medical'));
            modal.find('#view_net_payable').text(button.data('netpayable'));
            modal.find('#view_total_pension').text(button.data('totalpension'));

            // Dynamic Formulas
            var basic = button.data('basic');
            var percentage = button.data('percentage');
            var gross = button.data('gross');
            var commuted = button.data('commuted');
            var monthly = button.data('monthly');
            var medical = button.data('medical');
            var netpayable = button.data('netpayable');

            modal.find('#formula_gross').html('Formula: Last Basic Pay (' + basic + ') × (' + percentage + '% / 100)');
            modal.find('#formula_commuted').html('Formula: Gross Pension (' + gross + ') × 50% × 12 months × 10 years');
            modal.find('#formula_monthly').html('Formula: Gross Pension (' + gross + ') × 50%');
            modal.find('#formula_medical').html('Formula: Fixed Allowance');
            modal.find('#formula_net').html('Formula: Monthly Pension (' + monthly + ') + Medical Allowance (' + medical + ')');
            modal.find('#formula_total').html('Formula: Commuted Amount (' + commuted + ') + Net Monthly (' + netpayable + ')');

            // Status badge classes
            var status = button.data('status');
            var badge = modal.find('#view_status');
            badge.text(status);
            badge.removeClass('badge-success badge-info badge-warning badge-danger badge-secondary');
            
            if (status === 'Paid') badge.addClass('badge-success');
            else if (status === 'Processing') badge.addClass('badge-info');
            else if (status === 'Pending') badge.addClass('badge-warning');
            else if (status === 'Failed') badge.addClass('badge-danger');
            else badge.addClass('badge-secondary');
        });

        function toggleEditFields(status) {
            if (status === 'Paid') {
                $('#edit_bank_ref_group').show();
                $('#edit_paid_at_group').show();
            } else {
                $('#edit_bank_ref_group').hide();
                $('#edit_paid_at_group').hide();
            }
        }

        $('#edit_status').change(function() {
            toggleEditFields($(this).val());
        });
    });
</script>
@endpush
@endsection
