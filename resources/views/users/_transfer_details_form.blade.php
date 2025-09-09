<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Transfer Details</h4>
            <button type="button" class="btn btn-primary btn-sm" id="add-new-transfer-btn" data-toggle="collapse" data-target="#collapseOne">Add New Transfer</button>
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
                                        <input type="date" name="transfer_date" id="transfer_date" class="form-control">
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
                    </tr>
                </thead>
                <tbody id="transfer-details-table-body">
                    @if(isset($users) && $users->transferDetails->count() > 0)
                        @foreach($users->transferDetails as $transferDetail)
                            <tr data-id="{{ $transferDetail->id }}">
                                <td>{{ $transferDetail->transfer_date->format('Y-m-d') }}</td>
                                <td>{{ $transferDetail->oldBranchName->branch_name ?? 'N/A' }}</td>
                                <td>{{ $transferDetail->newBranchName->branch_name ?? 'N/A' }}</td>
                                <td>{{ $transferDetail->reason }}</td>
                                <td>{{ $transferDetail->status }}</td>
                                <td>
                                    @if($transferDetail->document)
                                        <a href="{{ asset($transferDetail->document) }}" target="_blank">View</a>
                                    @else
                                        N/A
                                    @endif
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

                console.log('Form Data:', formData); // Debugging statement
                console.log('Transfer Date from FormData:', formData.get('transfer_date')); // Debugging transfer_date

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Success Response:', response); // Debugging statement
                        alert(response.message);
                        transferAccordionCollapse.hide();
                        location.reload(); // For simplicity, reload page. In production, update table dynamically.
                    },
                    error: function(xhr) {
                        console.error('Error XHR:', xhr); // Debugging statement
                        alert('Error saving transfer detail: ' + xhr.responseText);
                    }
                });
            });

            
        });
    </script>
@endpush