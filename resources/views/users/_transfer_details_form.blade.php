<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0 fw-bold text-primary"><i class="im im-icon-Shuffle me-2"></i>Transfer Details</h4>
            <button class="btn btn-primary btn-sm px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                <i class="im im-icon-Add me-1"></i> Add New
            </button>
        </div>

        <!-- Accordion Form for Add/Edit -->
        <div class="accordion mb-4" id="transferDetailsAccordion">
            <div class="accordion-item border shadow-sm rounded">
                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#transferDetailsAccordion">
                    <div class="accordion-body bg-light p-4">
                        <h5 class="fw-bold mb-3 text-dark" id="transfer-form-title">Add / Edit Transfer Detail</h5>
                        <form id="transfer-detail-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="transfer-detail-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="transfer_datee" class="fw-bold mb-1">Transfer Date <span class="text-danger">*</span></label>
                                        <input type="date" name="transfer_date" id="transfer_datee" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="old_branch_display" class="fw-bold mb-1">Old Branch</label>
                                        <input type="text" id="old_branch_display" class="form-control" value="{{ $users->branch->branch_name ?? '' }}" readonly>
                                        <input type="hidden" name="old_branch" id="old_branch" value="{{ $users->branch->branch_name ?? '' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_branch" class="fw-bold mb-1">New Branch <span class="text-danger">*</span></label>
                                        <select name="new_branch" id="new_branch" class="form-select form-control" required>
                                            <option value="">Select New Branch</option>
                                            @foreach(\App\Models\Branch::all() as $branch)
                                                <option value="{{ $branch->branch_name }}">{{ $branch->branch_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status_transfer" class="fw-bold mb-1">Status</label>
                                        <select name="status" id="status_transfer" class="form-select form-control">
                                            <option value="Pending">Pending</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="reason_transfer" class="fw-bold mb-1">Transfer Reason / Order Details:</label>
                                <textarea name="reason" id="reason_transfer" class="form-control" rows="3" placeholder="Specify transfer reason..."></textarea>
                            </div>

                            <div class="form-group mt-3">
                                <label for="document_transfer" class="fw-bold mb-1">Transfer Order Document:</label>
                                <input type="file" name="document" id="document_transfer" class="form-control">
                                <small class="text-muted d-block mt-1" id="current-document-link-transfer"></small>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" class="btn btn-secondary me-2" id="cancel-transfer-btn">Cancel</button>
                                <button type="submit" class="btn btn-success px-4" id="save-transfer-detail-btn">
                                    <i class="im im-icon-Save me-1"></i> Save Transfer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Transfer Details in a Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle border mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Transfer Date</th>
                        <th>Old Branch</th>
                        <th>New Branch</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Document</th>
                        <th class="text-end pe-3" style="width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="transfer-details-table-body">
                    @if(isset($users) && $users->transferDetails->count() > 0)
                        @foreach($users->transferDetails as $transferDetail)
                            <tr data-id="{{ $transferDetail->id }}">
                                <td class="fw-bold text-dark">{{ is_object($transferDetail->transfer_date) ? $transferDetail->transfer_date->format('Y-m-d') : $transferDetail->transfer_date }}</td>
                                <td>{{ $transferDetail->old_branch ?? 'N/A' }}</td>
                                <td><span class="badge bg-primary">{{ $transferDetail->new_branch ?? 'N/A' }}</span></td>
                                <td>{{ $transferDetail->reason ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ strtolower($transferDetail->status ?? '') == 'approved' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ ucfirst($transferDetail->status ?? 'Pending') }}
                                    </span>
                                </td>
                                <td>
                                    @if($transferDetail->document)
                                        <a href="{{ asset($transferDetail->document) }}" target="_blank" class="badge bg-info text-decoration-none">
                                            <i class="fa fa-file-text-o me-1"></i> View File
                                        </a>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-transfer-btn" data-id="{{ $transferDetail->id }}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-transfer-btn" data-id="{{ $transferDetail->id }}" title="Edit Transfer">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-transfer-btn" data-id="{{ $transferDetail->id }}" title="Delete Transfer">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No transfer details found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Transfer Detail Modal -->
<div class="modal fade" id="viewTransferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white py-3">
                <h5 class="modal-title fw-bold text-white"><i class="im im-icon-Shuffle me-2"></i>Transfer Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 35%;">Transfer Date:</th>
                            <td class="fw-bold text-primary" id="view_tr_date">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Old Branch:</th>
                            <td id="view_tr_old_branch">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">New Branch:</th>
                            <td class="fw-bold text-success" id="view_tr_new_branch">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Status:</th>
                            <td id="view_tr_status">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Reason:</th>
                            <td id="view_tr_reason">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Order Document:</th>
                            <td id="view_tr_doc">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const userId = "{{ $users->id }}";
        const transferForm = $('#transfer-detail-form');

        function toggleTransferCollapse(show) {
            if (show) {
                $('#collapseSeven').collapse('show');
            } else {
                $('#collapseSeven').collapse('hide');
            }
        }

        function loadTransferDetails() {
            $.ajax({
                url: `/transferDetails/list/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';
                    if (res.transferDetails && res.transferDetails.length > 0) {
                        res.transferDetails.forEach(tr => {
                            let docHtml = tr.document 
                                ? `<a href="/${tr.document}" target="_blank" class="badge bg-info text-decoration-none"><i class="fa fa-file-text-o me-1"></i> View File</a>` 
                                : '<span class="text-muted small">N/A</span>';

                            let formattedDate = tr.transfer_date ? tr.transfer_date.split('T')[0] : 'N/A';

                            tableData += `<tr data-id="${tr.id}">
                                <td class="fw-bold text-dark">${formattedDate}</td>
                                <td>${tr.old_branch || 'N/A'}</td>
                                <td><span class="badge bg-primary">${tr.new_branch || 'N/A'}</span></td>
                                <td>${tr.reason || 'N/A'}</td>
                                <td><span class="badge ${tr.status === 'Approved' ? 'bg-success' : 'bg-warning text-dark'}">${tr.status || 'Pending'}</span></td>
                                <td>${docHtml}</td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-transfer-btn" data-id="${tr.id}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-transfer-btn" data-id="${tr.id}" title="Edit Transfer">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-transfer-btn" data-id="${tr.id}" title="Delete Transfer">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        tableData = '<tr><td colspan="7" class="text-center py-4 text-muted">No transfer details found.</td></tr>';
                    }
                    $('#transfer-details-table-body').html(tableData);
                },
                error: function(xhr) {
                    console.error('Error loading transfer table:', xhr.responseText);
                }
            });
        }

        loadTransferDetails();

        $('#cancel-transfer-btn').click(function() {
            transferForm[0].reset();
            $('#transfer-detail-id').val('');
            $('#current-document-link-transfer').html('');
            $('#transfer-form-title').text('Add / Edit Transfer Detail');
            toggleTransferCollapse(false);
        });

        transferForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const trId = $('#transfer-detail-id').val();
            const url = trId ? `/transferDetails/${trId}` : '/transferDetails';

            if (trId) {
                formData.append('_method', 'PATCH');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    loadTransferDetails();
                    alert(response.message || 'Transfer detail saved successfully.');
                    transferForm[0].reset();
                    $('#transfer-detail-id').val('');
                    $('#current-document-link-transfer').html('');
                    $('#transfer-form-title').text('Add / Edit Transfer Detail');
                    toggleTransferCollapse(false);
                },
                error: function(xhr) {
                    alert('Error saving transfer detail: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // View Transfer Modal
        $(document).on('click', '.view-transfer-btn', function() {
            const trId = $(this).data('id');
            $('#view_tr_date').text('Loading...');
            $('#view_tr_old_branch').text('Loading...');
            $('#view_tr_new_branch').text('Loading...');
            $('#view_tr_status').text('Loading...');
            $('#view_tr_reason').text('Loading...');
            $('#view_tr_doc').html('Loading...');

            $('#viewTransferModal').modal('show');

            $.ajax({
                url: `/transferDetails/${trId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const tr = response.transferDetail;
                    if (tr) {
                        $('#view_tr_date').text(tr.transfer_date ? tr.transfer_date.split('T')[0] : 'N/A');
                        $('#view_tr_old_branch').text(tr.old_branch || 'N/A');
                        $('#view_tr_new_branch').text(tr.new_branch || 'N/A');
                        $('#view_tr_status').text(tr.status || 'Pending');
                        $('#view_tr_reason').text(tr.reason || 'No reason specified');

                        if (tr.document) {
                            $('#view_tr_doc').html(`<a href="/${tr.document}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-external-link me-1"></i> Open Order Document</a>`);
                        } else {
                            $('#view_tr_doc').html('<span class="text-muted">No document attached</span>');
                        }
                    }
                },
                error: function(xhr) {
                    $('#view_tr_date').text('Error loading details');
                }
            });
        });

        // Edit Transfer
        $(document).on('click', '.edit-transfer-btn', function() {
            const trId = $(this).data('id');
            $.ajax({
                url: `/transferDetails/${trId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const tr = response.transferDetail;
                    if (tr) {
                        $('#transfer-detail-id').val(tr.id);
                        $('#transfer_datee').val(tr.transfer_date ? tr.transfer_date.split('T')[0] : '');
                        $('#old_branch').val(tr.old_branch);
                        $('#old_branch_display').val(tr.old_branch);
                        $('#new_branch').val(tr.new_branch);
                        $('#status_transfer').val(tr.status);
                        $('#reason_transfer').val(tr.reason);

                        if (tr.document) {
                            $('#current-document-link-transfer').html(`<a href="/${tr.document}" target="_blank" class="text-info fw-bold"><i class="fa fa-file-text-o me-1"></i> View Current Document</a>`);
                        } else {
                            $('#current-document-link-transfer').html('');
                        }

                        $('#transfer-form-title').text('Edit Transfer Detail #' + tr.id);
                        toggleTransferCollapse(true);

                        $('html, body').animate({
                            scrollTop: $('#transferDetailsAccordion').offset().top - 100
                        }, 300);
                    }
                },
                error: function(xhr) {
                    alert('Error fetching transfer detail: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // Delete Transfer
        $(document).on('click', '.delete-transfer-btn', function() {
            const trId = $(this).data('id');
            if (confirm('Are you sure you want to delete this transfer detail?')) {
                $.ajax({
                    url: `/transferDetails/${trId}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message || 'Deleted successfully.');
                        loadTransferDetails();
                    },
                    error: function(xhr) {
                        alert('Error deleting transfer detail: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                    }
                });
            }
        });
    });
</script>
@endpush
