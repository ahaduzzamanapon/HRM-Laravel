<div class="row">
    <div class="col-md-12">
        <h4>Promotion Details</h4>
        <hr>

        <!-- Add New Promotion Button (moved to top right) -->
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary" id="add-new-promotion-btn">Add New Promotion</button>
        </div>

        <!-- Accordion Form for Add/Edit (moved to top) -->
        <div class="accordion mb-4" id="promotionDetailsAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Promotion Details Form
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#promotionDetailsAccordion">
                    <div class="accordion-body">
                        <form id="promotion-detail-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="promotion-detail-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="promotion_date">Promotion Date:</label>
                                        <input type="date" name="promotion_date" id="promotion_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_designation">New Designation:</label>
                                        <input type="text" name="new_designation" id="new_designation" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="old_designation">Old Designation:</label>
                                        <input type="text" name="old_designation" id="old_designation" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pay_grade_change">Pay Grade Change:</label>
                                        <input type="checkbox" name="pay_grade_change" id="pay_grade_change" value="1" class="form-check-input">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_salary">New Salary:</label>
                                        <input type="number" name="new_salary" id="new_salary" class="form-control" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="document">Document:</label>
                                        <input type="file" name="document" id="document" class="form-control">
                                        <span id="current-document-link"></span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success" id="save-promotion-detail-btn">Save Promotion</button>
                            <div class="row">
    <div class="col-md-12">

        <div class="d-flex justify-content-between align-items-center">
            <h4>Promotion Details</h4>
            <button type="button" class="btn btn-primary btn-sm" id="add-new-promotion-btn" data-toggle="collapse" data-target="#collapseFive">Add New Promotion</button>
        </div>

        <!-- Accordion Form for Add/Edit (moved to top) -->
        <div class="accordion mb-4" id="promotionDetailsAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Promotion Details Form
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#promotionDetailsAccordion">
                    <div class="accordion-body">
                        <form id="promotion-detail-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="promotion-detail-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="promotion_date">Promotion Date:</label>
                                        <input type="date" name="promotion_date" id="promotion_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_designation">New Designation:</label>
                                        <input type="text" name="new_designation" id="new_designation" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="old_designation">Old Designation:</label>
                                        <input type="text" name="old_designation" id="old_designation" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pay_grade_change">Pay Grade Change:</label>
                                        <input type="checkbox" name="pay_grade_change" id="pay_grade_change" value="1" class="form-check-input">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_salary">New Salary:</label>
                                        <input type="number" name="new_salary" id="new_salary" class="form-control" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="document">Document:</label>
                                        <input type="file" name="document" id="document" class="form-control">
                                        <span id="current-document-link"></span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success" id="save-promotion-detail-btn">Save Promotion</button>
                            <button type="button" class="btn btn-secondary" id="cancel-promotion-edit-btn" data-toggle="collapse" data-target="#collapseFive">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Promotion Details in a Table (moved to bottom) -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Promotion Date</th>
                        <th>New Designation</th>
                        <th>Old Designation</th>
                        <th>Pay Grade Change</th>
                        <th>New Salary</th>
                        <th>Document</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="promotion-details-table-body">
                    @if(isset($users) && $users->promotionDetails->count() > 0)
                        @foreach($users->promotionDetails as $promotionDetail)
                            <tr data-id="{{ $promotionDetail->id }}">
                                <td>{{ $promotionDetail->promotion_date }}</td>
                                <td>{{ $promotionDetail->new_designation }}</td>
                                <td>{{ $promotionDetail->old_designation }}</td>
                                <td>{{ $promotionDetail->pay_grade_change ? 'Yes' : 'No' }}</td>
                                <td>{{ $promotionDetail->new_salary }}</td>
                                <td>
                                    @if($promotionDetail->document)
                                        <a href="{{ asset($promotionDetail->document) }}" target="_blank">View</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info edit-promotion-detail" data-id="{{ $promotionDetail->id }}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-promotion-detail" data-id="{{ $promotionDetail->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">No promotion details found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Promotion Details in a Table (moved to bottom) -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Promotion Date</th>
                        <th>New Designation</th>
                        <th>Old Designation</th>
                        <th>Pay Grade Change</th>
                        <th>New Salary</th>
                        <th>Document</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="promotion-details-table-body">
                    @if(isset($users) && $users->promotionDetails->count() > 0)
                        @foreach($users->promotionDetails as $promotionDetail)
                            <tr data-id="{{ $promotionDetail->id }}">
                                <td>{{ $promotionDetail->promotion_date }}</td>
                                <td>{{ $promotionDetail->new_designation }}</td>
                                <td>{{ $promotionDetail->old_designation }}</td>
                                <td>{{ $promotionDetail->pay_grade_change ? 'Yes' : 'No' }}</td>
                                <td>{{ $promotionDetail->new_salary }}</td>
                                <td>
                                    @if($promotionDetail->document)
                                        <a href="{{ asset($promotionDetail->document) }}" target="_blank">View</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info edit-promotion-detail" data-id="{{ $promotionDetail->id }}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-promotion-detail" data-id="{{ $promotionDetail->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">No promotion details found.</td>
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
            const promotionForm = $('#promotion-detail-form');
            const promotionAccordionCollapse = new bootstrap.Collapse($('#collapseFive'), { toggle: false });

            // Show form for adding new promotion
            $('#add-new-promotion-btn').click(function() {
                promotionForm[0].reset(); // Clear form
                $('#promotion-detail-id').val(''); // Clear ID for new entry
                $('#current-document-link').html(''); // Clear document link
                promotionAccordionCollapse.show(); // Show accordion
            });

            // Cancel button for form
            $('#cancel-promotion-edit-btn').click(function() {
                promotionAccordionCollapse.hide(); // Hide accordion
            });

            // Save Promotion Detail (Add/Edit)
            promotionForm.submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const promotionDetailId = $('#promotion-detail-id').val();
                const url = promotionDetailId ? `/promotionDetails/${promotionDetailId}` : '/promotionDetails';
                const method = promotionDetailId ? 'POST' : 'POST'; // Laravel uses POST for PUT/PATCH with _method field

                if (promotionDetailId) {
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
                        promotionAccordionCollapse.hide();
                        location.reload(); // For simplicity, reload page. In production, update table dynamically.
                    },
                    error: function(xhr) {
                        alert('Error saving promotion detail: ' + xhr.responseText);
                    }
                });
            });

            // Edit Promotion Detail
            $(document).on('click', '.edit-promotion-detail', function() {
                const promotionDetailId = $(this).data('id');
                $.ajax({
                    url: `/promotionDetails/${promotionDetailId}/edit`, // Laravel's edit route returns data for form
                    type: 'GET',
                    success: function(response) {
                        $('#promotion-detail-id').val(response.id);
                        $('#promotion_date').val(response.promotion_date);
                        $('#new_designation').val(response.new_designation);
                        $('#old_designation').val(response.old_designation);
                        $('#pay_grade_change').prop('checked', response.pay_grade_change);
                        $('#new_salary').val(response.new_salary);
                        if (response.document) {
                            $('#current-document-link').html(`<a href="${response.document}" target="_blank">View Current Document</a>`);
                        } else {
                            $('#current-document-link').html('');
                        }
                        promotionAccordionCollapse.show(); // Show accordion
                    },
                    error: function(xhr) {
                        alert('Error fetching promotion detail: ' + xhr.responseText);
                    }
                });
            });

            // Delete Promotion Detail
            $(document).on('click', '.delete-promotion-detail', function() {
                if (confirm('Are you sure you want to delete this promotion detail?')) {
                    const promotionDetailId = $(this).data('id');
                    $.ajax({
                        url: `/promotionDetails/${promotionDetailId}`,
                        type: 'POST', // Laravel uses POST for DELETE with _method field
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert(response.message);
                            $(`tr[data-id="${promotionDetailId}"]`).remove();
                            if ($('#promotion-details-table-body tr').length === 0) {
                                $('#promotion-details-table-body').html('<tr><td colspan="7" class="text-center">No promotion details found.</td></tr>');
                            }
                        },
                        error: function(xhr) {
                            alert('Error deleting promotion detail: ' + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endpush
