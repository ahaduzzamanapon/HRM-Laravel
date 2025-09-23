<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Transfer Details</h4>
            <button class="btn btn-primary btn-sm  col-md-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven"><i class="im im-icon-Add"></i> Add New</button>
        </div>
        <!-- Accordion Form for Add/Edit (moved to top) -->
        <div class="accordion mb-4" id="transferDetailsAccordion">
            <div class="accordion-item">
                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#transferDetailsAccordion">
                    <div class="accordion-body">
                        <form id="transfer-detail-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="transfer-detail-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="transfer_date">Transfer Date:</label>
                                        <input type="date" name="transfer_date" id="transfer_datee" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="old_branch">Old Branch:</label>
                                        <input type="text" id="old_branch_display" class="form-control" value="{{ $users->branch->branch_name ?? '' }}" readonly>
                                        <input type="hidden" name="old_branch" id="old_branch" value="{{ $users->branch->branch_name ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_branch">New Branch:</label>
                                        <select name="new_branch" id="new_branch" class="form-control">
                                            <option value="">Select New Branch</option>
                                            @foreach(\App\Models\Branch::all() as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status:</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="Pending">Pending</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="reason">Reason:</label>
                                <textarea name="reason" id="reason" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="document">Document:</label>
                                <input type="file" name="document" id="document" class="form-control">
                                <span id="current-document-link"></span>
                            </div>

                            <button type="submit" class="btn btn-success" id="save-transfer-detail-btn">Save Transfer</button>
                            <button type="button" class="btn btn-secondary" id="cancel-transfer-edit-btn">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Transfer Details in a Table (moved to bottom) -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Transfer Date</th>
                        <th>Old Branch</th>
                        <th>New Branch</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Document</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="transfer-details-table-body">
                    @if(isset($users) && $users->transferDetails->count() > 0)
                    @foreach($users->transferDetails as $transferDetail)
                            <tr data-id="{{ $transferDetail->id }}">
                                <td>{{ $transferDetail->transfer_date->format('Y-m-d') }}</td>
                                <td>{{ $transferDetail->old_branch ?? 'N/A' }}</td>
                                <td>{{ $transferDetail->new_branch ?? 'N/A' }}</td>
                                <td>{{ $transferDetail->reason }}</td>
                                <td>{{ $transferDetail->status }}</td>
                                <td>
                                    @if(isset($transferDetail) && !is_null($transferDetail->document))
                                        <a href="{{ asset($transferDetail->document) }}" target="_blank">View</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-transfer-btn" data-id="{{ $transferDetail->id }}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-transfer-btn" data-id="{{ $transferDetail->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">No transfer details found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const userId = "{{ $users->id }}"; // Laravel Blade user id
        const transferForm = $('#transfer-detail-form');
        const transferAccordionCollapse = new bootstrap.Collapse($('#collapseSeven'), { toggle: false });
        function loadTransferDetails() {
            $.ajax({
                url: `/transferDetails/list/${userId}`, // Laravel route
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    // return false;
                    console.log(res);
                    let tableData = '';
                    if (res.transferDetails.length > 0) {
                        res.transferDetails.forEach(tr => {
                            tableData += `
                                <tr data-id="${tr.id}">
                                    <td>${ new Date(tr.transfer_date).toISOString().split('T')[0] }</td>
                                    <td>${ tr.old_branch }</td>
                                    <td>${ tr.new_branch }</td>
                                    <td>${ tr.reason }</td>
                                    <td>${ tr.status }</td>
                                    <td>
                                        @if(isset($transferDetail) && $transferDetail->document)
                                            <a href="{{ asset($transferDetail->document) }}" target="_blank">View</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary edit-transfer-btn" data-id="{{ isset($transferDetail) ? $transferDetail->id : '' }}">Edit</button>
                                        <button class="btn btn-sm btn-danger delete-transfer-btn" data-id="{{ isset($transferDetail) ? $transferDetail->id : '' }}">Delete</button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        tableData = `<tr><td colspan="7" class="text-center">No transfer details found.</td></tr>`;
                    }
                    $('#transfer-details-table-body').html(tableData);
                },
                error: function(xhr) {
                    alert('Error loading transfer details: ' + xhr.responseText);
                }
            });
        }

        // Load transfers on page load
        loadTransferDetails();

        // Show form for adding new transfer
        $('#add-new-transfer-btn').click(function() {
            transferForm[0].reset();
            $('#transfer-detail-id').val('');
            $('#current-document-link').html('');
            transferAccordionCollapse.show();
        });

        // Cancel button
        $('#cancel-transfer-edit-btn').click(function() {
            transferAccordionCollapse.hide();
        });

        // Save Transfer Detail (Add/Edit)
        transferForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const transferDetailId = $('#transfer-detail-id').val();
            const url = transferDetailId ? `/transferDetails/${transferDetailId}` : '/transferDetails';
            const method = 'POST';

            if (transferDetailId) {
                formData.append('_method', 'PATCH'); // Spoof PATCH for Laravel
            }

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response.message);
                    transferAccordionCollapse.hide();
                    loadTransferDetails();
                },
                error: function(xhr) {
                    alert('Error saving transfer detail: ' + xhr.responseText);
                }
            });
        });

        // Edit Transfer Detail
        $(document).on('click', '.edit-transfer-btn', function() {
            const transferDetailId = $(this).data('id');
            $.ajax({
                url: `/transferDetails/${transferDetailId}/edit`,
                type: 'GET',
                success: function(response) {
                    // console.log(response.transferDetails.oldBranchName);
                     loadTransferDetails();
                    $('#transfer-detail-id').val(response.transferDetails.id);
                    $('#transfer_datee').val(new Date(response.transferDetails.transfer_date).toISOString().split('T')[0]);
                    $('#old_branch_display').val(response.transferDetails.old_branch);
                    $('#new_branch').val(response.transferDetails.new_branch).change();
                    $('#status').val(response.transferDetails.status);
                    $('#reason').val(response.transferDetails.reason);

                    if (response.transferDetails.document) {
                        $('#current-document-link').html(`<a href="${response.transferDetails.document}" target="_blank">View Current Document</a>`);
                    } else {
                        $('#current-document-link').html('');
                    }

                    transferAccordionCollapse.show();
                },
                error: function(xhr) {
                    alert('Error fetching transfer detail: ' + xhr.responseText);
                }
            });
        });

        // Delete Transfer Detail
        $(document).on('click', '.delete-transfer-btn', function() {
            if (confirm('Are you sure you want to delete this transfer detail?')) {
                const transferDetailId = $(this).data('id');
                $.ajax({
                    url: `/transferDetails/${transferDetailId}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                        loadTransferDetails();
                    },
                    error: function(xhr) {
                        alert('Error deleting transfer detail: ' + xhr.responseText);
                    }
                });
            }
        });
    });
</script>

@endpush
