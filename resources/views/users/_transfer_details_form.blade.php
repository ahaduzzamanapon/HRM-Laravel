<div class="row">
    <div class="col-md-12">
        <h4>Transfer Details</h4>
        <hr>

        <!-- Add New Transfer Button (moved to top right) -->
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary" id="add-new-transfer-btn">Add New Transfer</button>
        </div>

        <!-- Accordion Form for Add/Edit (moved to top) -->
        <div class="accordion mb-4" id="transferDetailsAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSeven">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                        Transfer Details Form
                    </button>
                </h2>
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
                                        <input type="date" name="transfer_date" id="transfer_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="old_branch">Old Branch:</label>
                                        <input type="text" name="old_branch" id="old_branch" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_branch">New Branch:</label>
                                        <input type="text" name="new_branch" id="new_branch" class="form-control">
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
                                <td>{{ $transferDetail->transfer_date }}</td>
                                <td>{{ $transferDetail->old_branch }}</td>
                                <td>{{ $transferDetail->new_branch }}</td>
                                <td>{{ $transferDetail->reason }}</td>
                                <td>{{ $transferDetail->status }}</td>
                                <td>
                                    @if($transferDetail->document)
                                        <a href="{{ asset($transferDetail->document) }}" target="_blank">View</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info edit-transfer-detail" data-id="{{ $transferDetail->id }}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-transfer-detail" data-id="{{ $transferDetail->id }}">Delete</button>
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

@section('footer_scripts')
@parent
<script>
    $(document).ready(function() {
        const transferForm = $('#transfer-detail-form');
        const transferAccordionCollapse = new bootstrap.Collapse($('#collapseSeven'), { toggle: false });

        // Show form for adding new transfer
        $('#add-new-transfer-btn').click(function() {
            transferForm[0].reset(); // Clear form
            $('#transfer-detail-id').val(''); // Clear ID for new entry
            $('#current-document-link').html(''); // Clear document link
            transferAccordionCollapse.show(); // Show accordion
        });

        // Cancel button for form
        $('#cancel-transfer-edit-btn').click(function() {
            transferAccordionCollapse.hide(); // Hide accordion
        });

        // Save Transfer Detail (Add/Edit)
        transferForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const transferDetailId = $('#transfer-detail-id').val();
            const url = transferDetailId ? `/transferDetails/${transferDetailId}` : '/transferDetails';
            const method = transferDetailId ? 'POST' : 'POST'; // Laravel uses POST for PUT/PATCH with _method field

            if (transferDetailId) {
                formData.append('_method', 'PATCH'); // Spoof PATCH method for Laravel
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
                    location.reload(); // For simplicity, reload page. In production, update table dynamically.
                },
                error: function(xhr) {
                    alert('Error saving transfer detail: ' + xhr.responseText);
                }
            });
        });

        // Edit Transfer Detail
        $(document).on('click', '.edit-transfer-detail', function() {
            const transferDetailId = $(this).data('id');
            $.ajax({
                url: `/transferDetails/${transferDetailId}/edit`, // Laravel's edit route returns data for form
                type: 'GET',
                success: function(response) {
                    $('#transfer-detail-id').val(response.id);
                    $('#transfer_date').val(response.transfer_date);
                    $('#old_branch').val(response.old_branch);
                    $('#new_branch').val(response.new_branch);
                    $('#reason').val(response.reason);
                    $('#status').val(response.status);
                    if (response.document) {
                        $('#current-document-link').html(`<a href="${response.document}" target="_blank">View Current Document</a>`);
                    } else {
                        $('#current-document-link').html('');
                    }
                    transferAccordionCollapse.show(); // Show accordion
                },
                error: function(xhr) {
                    alert('Error fetching transfer detail: ' + xhr.responseText);
                }
            });
        });

        // Delete Transfer Detail
        $(document).on('click', '.delete-transfer-detail', function() {
            if (confirm('Are you sure you want to delete this transfer detail?')) {
                const transferDetailId = $(this).data('id');
                $.ajax({
                    url: `/transferDetails/${transferDetailId}`,
                    type: 'POST', // Laravel uses POST for DELETE with _method field
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                        $(`tr[data-id="${transferDetailId}"]`).remove();
                        if ($('#transfer-details-table-body tr').length === 0) {
                            $('#transfer-details-table-body').html('<tr><td colspan="7" class="text-center">No transfer details found.</td></tr>');
                        }
                    },
                    error: function(xhr) {
                        alert('Error deleting transfer detail: ' + xhr.responseText);
                    }
                });
            }
        });
    });
</script>
@endsection